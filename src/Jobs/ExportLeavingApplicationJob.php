<?php

namespace Risky2k1\ApplicationManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Risky2k1\ApplicationManager\Models\Application;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExportLeavingApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected array $dataToDispatch;

    public function __construct($dataToDispatch)
    {
        $this->dataToDispatch = $dataToDispatch;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $query = Application::with('user')->where('type', $this->dataToDispatch['type']);

        if (isset($this->dataToDispatch['state'])) {
            $query->where('state', $this->dataToDispatch['state']);
        }

        $applications = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $leavingApplications = [
            ['Mã đơn từ', 'Tên người tạo', 'Trạng thái', 'Lý do', 'Loại đơn từ', 'Phòng ban', 'Mô tả', 'Người duyệt', 'Ngày tạo', 'Số ngày']
        ];

        foreach ($applications as $index => $application) {
            $rowData = [
                $application->code,
                $application->user->name,
                $application->state->text(),
                trans('application-manager::vi.'.$application->reason),
                trans('application-manager::vi.'.$application->type),
                $application->user->roles->first()?->text,
                $application->description,
                $application->reviewers->name,
                carbon($application->created_at, 'Y-m-d', 'd-m-Y'),
                $application->number_of_day_off,
            ];

            $leavingApplications[] = $rowData;
        }

        $sheet->fromArray($leavingApplications, null, 'A1');

        $headerRange = 'A1:'.$sheet->getHighestDataColumn().'1';
        $sheet->getStyle($headerRange)->getFill()->setFillType(FILL::FILL_SOLID)->getStartColor()->setARGB('FFA0A0A0');
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (!Storage::exists('exports/applications')) {
            Storage::makeDirectory('exports/applications', 0777, true);
        }

        $filename = storage_path('app/exports/applications/Danh_sách_đơn_từ_xin_nghỉ.xlsx');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save($filename);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}
