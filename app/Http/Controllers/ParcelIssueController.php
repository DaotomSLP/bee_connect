<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use Illuminate\Http\Request;
use App\Models\ParcelIssue;
use App\Models\ParcelIssueImage;
use App\Models\Refund;
use Carbon\Carbon;
use GImage\Image;
use Mpdf\Tag\A;

class ParcelIssueController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branchs = Branchs::all();

        $query = ParcelIssue::query()
            ->select(
                'parcel_issues.*',
                'branchs.branch_name',
                'users.name as user_name'
            )
            ->selectRaw('COALESCE(SUM(refunds.amount),0) as total_refund')
            ->join('users', 'parcel_issues.user_id', '=', 'users.id')
            ->join('branchs', 'parcel_issues.receiver_branch_id', '=', 'branchs.id')
            ->leftJoin('refunds', 'refunds.parcel_issue_id', '=', 'parcel_issues.id');

        if ($request->parcel_code) {
            $query->where('parcel_code', $request->parcel_code);
        }

        if ($request->received_at) {
            $query->whereDate('received_at', $request->received_at);
        }

        if ($request->receiver_branch_id) {
            $query->where('receiver_branch_id', $request->receiver_branch_id);
        }

        $query->groupBy(
            'parcel_issues.id',
            'parcel_issues.parcel_code',
            'parcel_issues.parcel_price',
            'parcel_issues.detail',
            'parcel_issues.status',
            'parcel_issues.receiver_branch_id',
            'parcel_issues.user_id',
            'parcel_issues.received_at',
            'parcel_issues.expired_at',
            'parcel_issues.created_at',
            'parcel_issues.updated_at',
            'branchs.branch_name',
            'users.name'
        );

        $all_price = $query->get();

        if ($request->page) {
            $query->offset(($request->page - 1) * 10);
        }

        $parcel_issues = $query
            ->orderByDesc('parcel_issues.id')
            ->limit(10)
            ->get();

        $pagination = [
            'offsets' => ceil(count($all_price) / 10),
            'offset' => $request->page ? $request->page : 1,
            'all' => count($all_price)
        ];

        return view('parcel-issues.index', compact('parcel_issues', 'pagination', 'branchs'));
    }

    public function insert(Request $request)
    {
        $parcel_issue = new ParcelIssue;
        $parcel_issue->parcel_code = $request->parcel_code;
        $parcel_issue->parcel_price = $request->parcel_price;
        $parcel_issue->detail = $request->detail;
        $parcel_issue->receiver_branch_id = $request->receiver_branch_id;
        $parcel_issue->received_at = $request->received_at;
        $parcel_issue->user_id = Auth()->user()->id;
        $parcel_issue->expired_at = Carbon::parse($request->received_at)->addMonth();

        if ($parcel_issue->save()) {
            return redirect('parcel-issues')->with(['error' => 'insert_success']);
        } else {
            return redirect('parcel-issues')->with(['error' => 'not_insert']);
        }
    }

    public function edit($id)
    {
        $parcel_issue = ParcelIssue::find($id);
        $branchs = Branchs::all();

        return view('parcel-issues.edit', compact('parcel_issue', 'branchs'));
    }

    public function update(Request $request)
    {
        $parcel_issue = ParcelIssue::find($request->id);
        $parcel_issue->parcel_code = $request->parcel_code;
        $parcel_issue->parcel_price = $request->parcel_price;
        $parcel_issue->detail = $request->detail;
        $parcel_issue->receiver_branch_id = $request->receiver_branch_id;
        $parcel_issue->received_at = $request->received_at;
        $parcel_issue->expired_at = Carbon::parse($request->received_at)->addMonth();

        if ($parcel_issue->save()) {
            return redirect('parcel-issues')->with(['error' => 'update_success']);
        } else {
            return redirect('parcel-issues')->with(['error' => 'not_update']);
        }
    }

    public function shipParcelIssue($id)
    {
        $parcel_issue = ParcelIssue::find($id);
        $parcel_issue->status = 'success';

        if ($parcel_issue->save()) {
            return redirect('parcel-issues')->with(['error' => 'ship_success']);
        } else {
            return redirect('parcel-issues')->with(['error' => 'not_ship']);
        }
    }

    public function refundParcelIssue($id)
    {
        $parcel_issue = ParcelIssue::find($id);
        $total_refund = Refund::where('parcel_issue_id', $id)->sum('amount');

        $refunds = Refund::query()
            ->select('refunds.*', 'users.name as user_name')
            ->join('users', 'users.id', '=', 'refunds.user_id')
            ->where('parcel_issue_id', $id)->get();

        return view('parcel-issues.refund', compact('parcel_issue', 'refunds', 'total_refund'));
    }

    public function insertRefund(Request $request)
    {
        $refund = new Refund;
        $refund->amount = $request->amount;
        $refund->parcel_issue_id = $request->parcel_issue_id;
        $refund->user_id = Auth()->user()->id;
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');

            // ตรวจสอบว่าเป็นรูปภาพ
            $request->validate([
                'receipt' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB
            ]);

            $fileName = 'receipt_' . time() . '.jpg';
            $path = $_SERVER['DOCUMENT_ROOT'] . '/img/receipts/' . $fileName;
            $image = new Image();
            $image->load($file)
                ->resizeToWidth(300) // ปรับขนาดตามความกว้าง
                ->save($path);

            $receiptPath = $fileName;
        }

        if ($receiptPath) {
            $refund->receipt_image = $receiptPath;
        }

        if ($refund->save()) {

            $parcel_issue = ParcelIssue::find($request->parcel_issue_id);
            $parcel_issue->status = 'refund';
            $parcel_issue->save();

            return redirect('refund-parcel-issue/' . $request->parcel_issue_id)->with(['error' => 'refund_success']);
        } else {
            return redirect('refund-parcel-issue/' . $request->parcel_issue_id)->with(['error' => 'not_refund']);
        }
    }

    public function parcelIssueImages($id)
    {
        $parcel_issue = ParcelIssue::find($id);
        $images = ParcelIssueImage::where('parcel_issue_id', $id)->get();

        return view('parcel-issues.images', compact('parcel_issue', 'images'));
    }

    public function addParcelIssueImages(Request $request)
    {
        $parcel_issue_image = new ParcelIssueImage;
        $parcel_issue_image->parcel_issue_id = $request->parcel_issue_id;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // ตรวจสอบว่าเป็นรูปภาพ
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB
            ]);

            $fileName = 'parcel_issue_' . time() . '.jpg';
            $path = $_SERVER['DOCUMENT_ROOT'] . '/img/receipts/' . $fileName;
            $image = new Image();
            $image->load($file)
                ->resizeToWidth(300) // ปรับขนาดตามความกว้าง
                ->save($path);

            $imagePath = $fileName;
        }

        if ($imagePath) {
            $parcel_issue_image->image = $imagePath;
        }

        if ($parcel_issue_image->save()) {
            return redirect('parcel-issue-images/' . $request->parcel_issue_id)->with(['error' => 'insert_success']);
        } else {
            return redirect('parcel-issue-images/' . $request->parcel_issue_id)->with(['error' => 'not_insert']);
        }
    }
}
