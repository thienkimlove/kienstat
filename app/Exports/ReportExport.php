<?php

namespace App\Exports;


use App\Lib\Base;
use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    public $status;
    public $from_date;
    public $to_date;


    public function __construct($data)
    {
        $this->status = isset($data['filter_status'])? $data['filter_status'] : null;

        $this->from_date = isset($data['filter_from'])? $data['filter_from'] : null;
        $this->to_date = isset($data['filter_to'])? $data['filter_to'] : null;


    }

    public function collection()
    {
        $contents = Report::query();
        if ($this->status) {
            $contents = $contents->where('status', $this->status);
        }
        if ($this->from_date) {
            $contents = $contents->whereDate('created_at', '>=', $this->from_date);
        }
        if ($this->to_date) {
            $contents = $contents->whereDate('created_at', '<=', $this->to_date);
        }
        $contents = $contents->get();
        //dd($contents->count());
        $reports = [];
        foreach ($contents as $index => $row) {

            $reports[] = array(
                '0' => $index,
                '1' => $row->code,
                '2' => $row->content,
                '3' => $row->name,
                '4' => $row->address,
                '5' => $row->phone,
                '6' => number_format($row->amount)." VND",
                '7' => $row->quantity,
                '8' => $row->seller,
                '9' => $row->date,
                '10' => Base::getStatus()[$row->status],
                '11' => $row->note,

            );
        }

        return (collect($reports));
    }
    public function headings(): array
    {
        return [
            'STT',
            'Mã vận đơn',
            'Nội dung',
            'Tên người nhận',
            'Địa chỉ',
            'Số ĐT',
            'Số tiền',
            'Số lượng',
            'Người bán',
            'Ngày đặt hàng',
            'Trạng thái',
            'Ghi chú',
        ];
    }
}
