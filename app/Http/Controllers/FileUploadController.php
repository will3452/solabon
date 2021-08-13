<?php

namespace App\Http\Controllers;

use App\Models\File;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class Person
{
    public $name;
    public $ref;
    public $dept;
    public $records;
    public $total;
    public function __construct()
    {
        $this->records = collect([]);
    }
    public function addRecords($newrecord)
    {
        $this->records->push($newrecord);
    }
}

class FileUploadController extends Controller
{

    public function storeFile()
    {
        $data = request()->validate([
            'file' => 'required|mimes:xls',
            'name' => 'required|unique:files,name',
        ]);

        //save file
        $data['path'] = $data['file']->store('public/files');
        unset($data['file']);

        File::create($data);

        alert('file was submitted!', 'success');
        return back();
    }

    public function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) {return $a !== null;}));
    }

    public function removeNullRow($data)
    {
        $newarr = [];
        $remove = [
            'Timekeeping System',
            'EMPLOYEE ATTENDANCE LOGS',
            'ID No. :',
            'Dept. :',
            'Name :',
        ];
        foreach ($data as $d) {
            for ($i = 0; $i < count($d); $i++) {
                !in_array($d[$i], $remove) ?: $d[$i] = null;
            }
            if (!$this->containsOnlyNull($d)) {
                array_push($newarr, $d);
            }
        }

        // dd($newarr[10]);
        return $newarr;
    }

    public function calculateTotal($str_total)
    {
        $orig_arr = explode('=', $str_total);
        $orig_arr = end($orig_arr);
        $orig_str = \Str::of($orig_arr)->trim();
        $arr_total = explode(' ', $orig_str);
        $hours = $arr_total[0];
        $mins = $arr_total[2];
        return (int) $hours + ((float) $mins / 60);

    }

    public function initRecords($arr, $persons)
    {
        $len = count($arr);
        $i = 0;
        while ($i < $len) {
            $person = new Person();
            if (!empty($arr[$i][4]) && !empty($arr[$i][12])) {
                $person->ref = $arr[$i][4];
                $person->dept = $arr[$i][12];
                $i++;
                $person->name = $arr[$i][4];
                $i++;
            }
            if ($arr[$i][2] === "DATE") {
                $i++;
            }
            $totalIsZero = false;
            while ($arr[$i][2] != null) {
                if (($arr[$i][6] != null || $arr[$i][6] != 0) &&
                    ($arr[$i][11] == null || $arr[$i][11] == 0)) {
                    $totalIsZero = true;
                }
                $person->addRecords([
                    'date' => $arr[$i][2],
                    'time_in' => $arr[$i][6],
                    'time_out' => $arr[$i][11],
                ]);
                $i++;
            }
            if ($arr[$i][7] != null) {
                $total = $this->calculateTotal($arr[$i][7]);
                $person->total = $totalIsZero ? '0.00' : number_format($total, 2);
            }

            if (!empty($person->name)) {
                $persons->push($person);
            }
            $i++;
        }
        return $persons;
    }

    public function readFile($path, $persons)
    {

        $reader = new Xls();
        $data = $reader->load(storage_path('app\\' . $path));
        $arr = $data->getSheet(0)->toArray();
        $arr = $this->removeNullRow($arr);

        return $this->initRecords($arr, $persons);
    }

    public function processRecords($id)
    {
        $persons = collect([]);
        $file = File::findOrFail($id);
        $persons = $this->readFile($file->path, $persons);
        $records = $persons->groupBy('dept');
        $dates = $persons->first()->records->pluck('date');
        $dates = $dates->map(function ($item, $key) {
            $d = Carbon::parse($item);
            return collect([
                'day' => $d->isoFormat('dddd'),
                'orig' => $item,
                'date' => $d->isoFormat('MMMM D Y'),
                'month' => $d->isoFormat('MMMM'),
                'year' => $d->isoFormat('Y'),
                'date_day' => $d->format('d'),
            ]);
        });
        $first = $dates->first();
        $last = $dates->last();

        $title = $first['month'] . ' ' . $first['date_day'] . ' to ' .
            $last['month'] . ' ' . $last['date_day'] . ', ' . $first['year'];
        if ($first['month'] === $last['month']) {
            $title = $first['month'] . ' ' . $first['date_day'] . ' to ' .
                $last['date_day'] . ', ' . $first['year'];
        }

        return [
            $title,
            $records,
            $file,
            $dates,
        ];

    }

    public function show($id)
    {
        [$title, $records, $file, $dates] = $this->processRecords($id);
        return view('records.show', compact('records', 'file', 'dates', 'title'));
    }

    public function destroy($id)
    {
        File::find($id)->delete();
        alert('Removed Done!', 'success');
        return back();
    }

    function print($id) {
        [$title, $records, $file, $dates] = $this->processRecords($id);
        return view('print', compact('records', 'file', 'dates', 'title'));
    }
}
