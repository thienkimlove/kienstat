<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Base;
use App\Models\Report;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportRequest as StoreRequest;
use App\Http\Requests\ReportRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Prologue\Alerts\Facades\Alert;

/**
 * Class ReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Report');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report');
        $this->crud->setEntityNameStrings('Báo cáo', 'Báo cáo');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        $this->crud->addColumns([

            [
                'name' => 'code',
                'label' => 'Mã vận đơn ',
            ],

            [
                'name' => 'content',
                'label' => 'Nội dung',
            ],
            [
                'name' => 'name',
                'label' => 'Tên người nhận',
            ],

            [
                'name' => 'address',
                'label' => 'Địa chỉ',
            ],

            [
                'name' => 'phone',
                'label' => 'SĐT',
            ],

            [
                'name' => 'amount',
                'label' => 'Số tiền',
                'type' => 'number'
            ],

            [
                'name' => 'quantity',
                'label' => 'Số lượng',
            ],

            [
                'name' => 'seller',
                'label' => 'Người bán',
            ],
            [
                'name' => 'note',
                'label' => 'Ghi chú',
            ],

            [
                'name' => 'date',
                'label' => 'Ngày đặt hàng',
                //'type' => "date",
            ],
            [
                'name' => 'status',
                'label' => 'Trạng thái',
                'type' => 'select_from_array',
                'options' => Base::getStatus()
            ],
        ]);




        $this->crud->addFields([


            [
                'name' => 'content',
                'label' => 'Nội dung',
                'type' => 'textarea'
            ],
            [
                'name' => 'name',
                'label' => 'Tên người nhận',
            ],

            [
                'name' => 'address',
                'label' => 'Địa chỉ',
                'type' => 'textarea'
            ],

            [
                'name' => 'phone',
                'label' => 'SĐT',
            ],

            [
                'name' => 'amount',
                'label' => 'Số tiền',
                'type' => 'number_decimal',

            ],

            [
                'name' => 'quantity',
                'label' => 'Số lượng',
            ],


            [
                'name' => 'note',
                'label' => 'Ghi chú',
                'type' => 'textarea'
            ],


            [
                'name' => 'status',
                'label' => 'Trạng thái',
                'type' => 'select_from_array',
                'options' => Base::getStatus()
            ],
        ]);


        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'CHỌN THỜI GIAN'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'date', '>=', $dates->from);
                $this->crud->addClause('where', 'date', '<=', $dates->to);
            });

        $this->crud->addFilter([ // select2 filter
            'name' => 'status',
            'type' => 'select2',
            'label'=> 'Trạng thái'
        ], function() {
            return Base::getStatus();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'status', $value);
        });

        $this->crud->orderBy('created_at', 'desc');

        if (!backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'user_id', backpack_user()->id);
        }
        //$this->crud->enableDetailsRow();
        //$this->crud->allowAccess('details_row');

        $this->crud->enableExportButtons();


//        if (backpack_user()->can('system')) {
//            $this->crud->addButtonFromView('line', 'promotion_details', 'promotion_details', 'end');
//            $this->crud->addButtonFromView('line', 'change_promotion', 'change_promotion', 'end');
//        }

        // add asterisk for fields that are required in ReportRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function create()
    {
        if (!backpack_user()->transport_code) {
            Alert::error('Xin nhập vào mã giao vận gốc cho tài khoản của bạn!')->flash();

            return redirect('admin/edit-account-info');
        }
        return parent::create();
    }

    public function store(StoreRequest $request)
    {

        if (!backpack_user()->transport_code) {
            Alert::error('Xin nhập vào mã giao vận gốc cho tài khoản của bạn!')->flash();

            return redirect('admin/dashboard');
        }


        $request->request->set('amount', str_replace('.', '', $request->input('amount')));

        $request->request->set('user_id', backpack_user()->id);

        $phoneCreate = $request->input('phone');

        if ($phoneCreate) {

            $existedCountPhone = Report::where('phone', $phoneCreate)
                ->whereDate('created_at', Carbon::today())
                ->count();

            if ($existedCountPhone > 0) {

                Alert::error('Đã có đơn trong ngày với số điện thoại '.$phoneCreate.'!')->flash();

                return back()->withInput();
            }
        }



        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        $phoneCreate = $request->input('phone');

        if ($phoneCreate) {

            $existedCountPhone = Report::where('phone', $phoneCreate)
                ->whereDate('date', Carbon::today())
                ->where('id', '!=', $request->input('id'))
                ->count();

            if ($existedCountPhone > 0) {

                Alert::error('Đã có đơn trong ngày với số điện thoại '.$phoneCreate.'!')->flash();

                return back()->withInput();
            }
        }


        $request->request->set('amount', str_replace('.', '', $request->input('amount')));
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
