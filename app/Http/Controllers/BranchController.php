<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Provinces;
use GImage\Image;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BranchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $provinces = Provinces::all();
        $districts = Districts::all();
        $pagination = [
            'offsets' =>  ceil(sizeof(Branchs::select('branchs.*', 'districts.dist_name', 'provinces.prov_name')
                ->join('districts', 'branchs.district_id', 'districts.id')
                ->join('provinces', 'districts.prov_id', 'provinces.id')
                ->where('branchs.enabled', '1')
                ->get()) / 10),
            'offset' => 1,
            'all' => sizeof(Branchs::select('branchs.*', 'districts.dist_name', 'provinces.prov_name')
                ->join('districts', 'branchs.district_id', 'districts.id')
                ->join('provinces', 'districts.prov_id', 'provinces.id')
                ->where('branchs.enabled', '1')
                ->get())
        ];
        $branchs = Branchs::select('branchs.*', 'districts.dist_name', 'provinces.prov_name')
            ->join('districts', 'branchs.district_id', 'districts.id')
            ->join('provinces', 'districts.prov_id', 'provinces.id')
            ->where('branchs.enabled', '1')
            ->orderBy('branchs.id', 'desc')
            ->limit(10)
            ->get();
        return view('branch', compact('provinces', 'districts', 'branchs', 'pagination'));
    }

    public function pagination($offset)
    {
        $provinces = Provinces::all();
        $districts = Districts::all();
        $pagination = [
            'offsets' =>  ceil(sizeof(Branchs::select('branchs.*', 'districts.dist_name', 'provinces.prov_name')
                ->join('districts', 'branchs.district_id', 'districts.id')
                ->join('provinces', 'districts.prov_id', 'provinces.id')
                ->where('branchs.enabled', '1')
                ->get()) / 10),
            'offset' => $offset,
            'all' => sizeof(Branchs::select('branchs.*', 'districts.dist_name', 'provinces.prov_name')
                ->join('districts', 'branchs.district_id', 'districts.id')
                ->join('provinces', 'districts.prov_id', 'provinces.id')
                ->where('branchs.enabled', '1')
                ->get())
        ];
        $branchs = Branchs::select('branchs.*', 'districts.dist_name', 'provinces.prov_name')
            ->join('districts', 'branchs.district_id', 'districts.id')
            ->join('provinces', 'districts.prov_id', 'provinces.id')
            ->where('branchs.enabled', '1')
            ->orderBy('branchs.id', 'desc')
            ->offset(($offset - 1) * 10)
            ->limit(10)
            ->get();
        return view('branch', compact('provinces', 'districts', 'branchs', 'pagination'));
    }

    public function insert(Request $request)
    {
        $branch = new Branchs;
        $branch->first_name = $request->first_name;
        $branch->last_name = $request->last_name;
        $branch->phone = $request->phone;
        $branch->branch_name = $request->branch_name;
        $branch->district_id = $request->district_id;
        $branch->is_owner = "0";
        $branch->enabled = '1';

        if ($branch->save()) {
            return redirect('branchs')->with(['error' => 'insert_success']);
        } else {
            return redirect('branchs')->with(['error' => 'not_insert']);
        }
    }

    public function edit($id)
    {
        $provinces = Provinces::all();
        $districts = Districts::all();
        $branch = Branchs::select('branchs.*', 'provinces.id as prov_id', 'districts.id as dist_id')
            ->where('branchs.id', $id)
            ->join('districts', 'districts.id', 'branchs.district_id')
            ->join('provinces', 'provinces.id', 'districts.prov_id')
            ->first();
        return view('editBranch', compact('provinces', 'branch', 'districts', 'provinces'));
    }

    public function update(Request $request)
    {
        $branch = [
            'district_id' => $request->district_id,
            'branch_name' => $request->branch_name,
            'phone' => $request->phone,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'is_owner' => '0',
            'enabled' => '1'
        ];

        if (Branchs::where('id', $request->id)->update($branch)) {
            return redirect('branchs')->with(['error' => 'insert_success']);
        } else {
            return redirect('branchs')->with(['error' => 'not_insert']);
        }
    }

    public function delete($id)
    {
        $branch = [
            'enabled' => '0'
        ];

        if (Branchs::where('id', $id)->update($branch)) {
            return redirect('branchs')->with(['error' => 'delete_success']);
        } else {
            return redirect('branchs')->with(['error' => 'not_insert']);
        }
    }

    public function editBranchLogo()
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $branch = Branchs::select('branchs.*')
            ->where('branchs.id', Auth::user()->branch_id)
            ->first();

        return view('editBranchLogo', compact('branch'));
    }

    public function updateBranchLogo(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        // validate รูป
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
        ]);

        // ดึงข้อมูล branch ปัจจุบัน
        $branch = Branchs::where('id', Auth::user()->branch_id)->first();

        if (!$branch) {
            return redirect('editBranchLogo')->with(['error' => 'not_found']);
        }

        // ===== ลบรูปเก่า =====
        if (!empty($branch->logo_image)) {
            $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/img/logos/' . $branch->logo_image;
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        // ===== บันทึกรูปใหม่ =====
        $file = $request->file('logo');
        $fileName = 'logo_' . time() . '.jpg';
        $path = $_SERVER['DOCUMENT_ROOT'] . '/img/logos/' . $fileName;

        $image = new Image();
        $image->load($file)
            ->resizeToWidth(300)
            ->save($path);

        try {
            Branchs::where('id', $branch->id)->update([
                'logo_image' => $fileName
            ]);

            return redirect('editBranchLogo')->with(['success' => 'insert_success']);
        } catch (\Exception $e) {
            return redirect('editBranchLogo')->with(['error' => 'not_insert']);
        }
    }
}
