<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Expenditure;
use App\Models\ExpenditureImages;
use App\Models\Lots;
use Faker\Core\Number;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use PDF;

class ExpenditureController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $to_date_now = date('Y-m-d', strtotime(Carbon::now()));

        if ($request->date != '') {
            $date = $request->date;
            $to_date = $request->to_date;
            $date_now = date('Y-m-d', strtotime($request->date));
            $to_date_now = date('Y-m-d', strtotime($request->to_date));
        } else {
            $date = [Carbon::today()->toDateString()];
            $to_date = [Carbon::today()->toDateString()];
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        $result = Expenditure::query();

        $result->select('expenditure.*', 'users.name')
            ->join('users', 'expenditure.user_id', 'users.id')
            ->whereBetween('expenditure.created_at', [$date, $to_date]);

        if ($request->date_search != '') {
        }

        $all_expenditure = $result->orderBy('expenditure.id', 'desc')->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $expenditure = $result
            ->orderBy('expenditure.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil(sizeof($all_expenditure) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_expenditure),
        ];

        return view('expenditure', compact('expenditure', 'pagination', 'date_now', 'to_date_now'));
    }

    public function insert(Request $request)
    {
        $expenditure = new Expenditure();
        $expenditure->created_at = $request->date;
        $expenditure->price = $request->price;
        $expenditure->user_id = Auth::user()->id;
        $expenditure->detail = $request->detail;

        if ($expenditure->save()) {
            return redirect('expenditure')->with(['error' => 'insert_success']);
        } else {
            return redirect('expenditure')->with(['error' => 'not_insert']);
        }
    }

    public function editExpenditure($id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $expenditure = Expenditure::where('id', $id)->first();

        return view('editExpenditure', compact('expenditure'));
    }

    public function updateExpenditure(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $expenditure = [
            'created_at' => $request->date,
            'price' => $request->price,
            'detail' => $request->detail,
        ];

        try {
            // Attempt to update the record
            $affectedRows = Expenditure::where('id', $request->id)->update($expenditure);
            return redirect('expenditure')->with(['error' => 'insert_success']);
        } catch (QueryException $e) {
            return redirect('expenditure')->with(['error' => 'not_insert']);
        }
    }

    public function expenditureImages($id)
    {
        $expenditureImages = ExpenditureImages::select('expenditureImages.*')
            ->join('expenditure', 'expenditureImages.expen_id', 'expenditure.id')
            ->where('expenditure.id', $id)
            ->orderBy('expenditureImages.id', 'desc')
            ->get();
        return view('expenditureImages', compact('expenditureImages', 'id'));
    }

    public function addExpenditureImages(Request $request)
    {
        if ($request->hasFile('img_src')) {
            $image = $request->file('img_src');
            $reImage = time() . '.' . $image->getClientOriginalExtension();
            $dest = './img/design/slide';
            $image->move($dest, $reImage);

            $expenditureImage = new ExpenditureImages;
            $expenditureImage->img_src = $reImage;
            $expenditureImage->expen_id = $request->expen_id;

            if ($expenditureImage->save()) {
                return redirect('expenditureImages/' . $request->expen_id)->with(['error' => 'insert_success']);
            } else {
                return redirect('expenditureImages/' . $request->expen_id)->with(['error' => 'not_insert']);
            }
        } else {
            return redirect('expenditureImages/' . $request->expen_id)->with(['error' => 'not_insert']);
        }
    }

    public function deleteExpenditureImages($id, $expen_id)
    {
        $expenditureImage = ExpenditureImages::where('id', $id)->first();
        $file_path = './img/design/slide/' . $expenditureImage->img_src;
        unlink($file_path);
        if (ExpenditureImages::where('id', $id)->delete()) {
            return redirect('expenditureImages/' . $expen_id)->with(['error' => 'delete_success']);
        } else {
            return redirect('expenditureImages/' . $expen_id)->with(['error' => 'not_insert']);
        }
    }

    public function report(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        if ($request->date != '') {
            $date = $request->date;
            $to_date = $request->to_date;
        } else {
            $date = [Carbon::today()->toDateString()];
            $to_date = [Carbon::today()->toDateString()];
        }

        $result = Expenditure::query();

        $result->select('expenditure.*', 'users.name')
            ->join('users', 'expenditure.user_id', 'users.id')
            ->whereBetween('expenditure.created_at', [$date, $to_date]);

        if ($request->date_search != '') {
        }

        $expenditure = $result
            ->orderBy('expenditure.id', 'desc')
            ->get();

        $totalExpenditure = $expenditure->reduce(function ($carry, $expen) : int {
            return $carry + $expen->price;
        });

        $data = [
            'date' => $date,
            'to_date' => $to_date,
            'expenditure' => $expenditure,
            'totalExpenditure' => $totalExpenditure
        ];

        $pdf = PDF::loadView(
            'pdf.expen',
            $data,
            [],
            [
                'format' => 'A4',
                'custom_font_dir' => base_path('resources/fonts/'),
                'custom_font_data' => [
                    'defago' => [ // must be lowercase and snake_case
                        'R'  => 'defago-noto-sans-lao.ttf',    // regular font
                        'B'  => 'DefagoNotoSansLaoBold.ttf',    // bold font
                    ]
                  // ...add as many as you want.
                ]
            ]
        );
        return $pdf->stream('document.pdf');
    }
}
