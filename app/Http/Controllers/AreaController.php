<?php
namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Salesman;
use App\Models\Customer;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $searchResults = Area::where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%")
                  ->orderBy('city')->orderBy('code')->get();
            return view('area.index', compact('searchResults'));
        }

        $provinces = Area::select('province')->distinct()->orderBy('province')->pluck('province');
        
        $selectedProvince = $request->province;
        $cities = [];
        if ($selectedProvince) {
            $cities = Area::where('province', $selectedProvince)->select('city')->distinct()->orderBy('city')->pluck('city');
        }

        $selectedCity = $request->city;
        $kecamatans = [];
        if ($selectedProvince && $selectedCity) {
            $kecamatans = Area::where('province', $selectedProvince)->where('city', $selectedCity)->orderBy('code')->get();
        }

        return view('area.index', compact('provinces', 'selectedProvince', 'cities', 'selectedCity', 'kecamatans'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Hanya Admin yang diizinkan untuk mengelola wilayah.');

        $actionType = $request->input('action_type');

        if ($actionType === 'province') {
            $validated = $request->validate([
                'province_name' => 'required|string|max:255',
                'province_code' => 'required|string|min:2|max:5',
                'city_name' => 'required|string|max:255',
                'city_code' => 'required|string|min:2|max:5',
                'kecamatan_name' => 'required|string|max:255',
                'kecamatan_code' => 'required|string|min:1|max:5',
            ]);

            $fullCode = strtoupper($validated['province_code'] . '-' . $validated['city_code'] . '-' . $validated['kecamatan_code']);

            $request->merge(['full_code' => $fullCode]);
            $request->validate(['full_code' => 'unique:areas,code']);

            Area::create([
                'province' => $validated['province_name'],
                'city' => $validated['city_name'],
                'name' => $validated['kecamatan_name'],
                'code' => $fullCode,
            ]);

            return redirect()->route('area.index', [
                'province' => $validated['province_name'],
                'city' => $validated['city_name']
            ])->with('success', 'Provinsi baru berhasil ditambahkan');
        }

        if ($actionType === 'city') {
            $validated = $request->validate([
                'province' => 'required|string|max:255',
                'city_name' => 'required|string|max:255',
                'city_code' => 'required|string|min:2|max:5',
                'kecamatan_name' => 'required|string|max:255',
                'kecamatan_code' => 'required|string|min:1|max:5',
            ]);

            $provCode = Area::getProvinceCodeByName($validated['province']);
            $fullCode = strtoupper($provCode . '-' . $validated['city_code'] . '-' . $validated['kecamatan_code']);

            $request->merge(['full_code' => $fullCode]);
            $request->validate(['full_code' => 'unique:areas,code']);

            Area::create([
                'province' => $validated['province'],
                'city' => $validated['city_name'],
                'name' => $validated['kecamatan_name'],
                'code' => $fullCode,
            ]);

            return redirect()->route('area.index', [
                'province' => $validated['province'],
                'city' => $validated['city_name']
            ])->with('success', 'Kota baru berhasil ditambahkan');
        }

        if ($actionType === 'kecamatan') {
            $validated = $request->validate([
                'province' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'kecamatan_name' => 'required|string|max:255',
                'kecamatan_code' => 'required|string|min:1|max:5',
            ]);

            $provCode = Area::getProvinceCodeByName($validated['province']);
            $cityCode = Area::getCityCodeByName($validated['province'], $validated['city']);
            $fullCode = strtoupper($provCode . '-' . $cityCode . '-' . $validated['kecamatan_code']);

            $request->merge(['full_code' => $fullCode]);
            $request->validate(['full_code' => 'unique:areas,code']);

            Area::create([
                'province' => $validated['province'],
                'city' => $validated['city'],
                'name' => $validated['kecamatan_name'],
                'code' => $fullCode,
            ]);

            return redirect()->route('area.index', [
                'province' => $validated['province'],
                'city' => $validated['city']
            ])->with('success', 'Kecamatan baru berhasil ditambahkan');
        }

        return redirect()->route('area.index')->with('error', 'Aksi tidak valid');
    }

    public function edit(Area $area)
    {
        abort(404);
    }

    public function update(Request $request, Area $area)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Hanya Admin yang diizinkan untuk mengelola wilayah.');

        $actionType = $request->input('action_type');

        if ($actionType === 'province') {
            $validated = $request->validate([
                'old_province_name' => 'required|string|max:255',
                'new_province_name' => 'required|string|max:255',
                'province_code' => 'required|string|min:2|max:5',
            ]);

            $oldName = $validated['old_province_name'];
            $newName = $validated['new_province_name'];
            $newCode = strtoupper($validated['province_code']);

            // Fetch all areas in this province and cascade update names/codes
            $areas = Area::where('province', $oldName)->get();

            foreach ($areas as $a) {
                $parts = explode('-', $a->code);
                $oldAreaCode = $a->code;

                if (count($parts) === 3) {
                    $parts[0] = $newCode;
                    $newAreaCode = implode('-', $parts);
                } else {
                    $newAreaCode = $a->code;
                }

                $a->province = $newName;
                $a->code = $newAreaCode;
                $a->save();

                if ($oldAreaCode !== $newAreaCode) {
                    Salesman::where('area', $oldAreaCode)->update(['area' => $newAreaCode]);
                    \DB::table('suppliers')->where('area_code', $oldAreaCode)->update(['area_code' => $newAreaCode]);
                }
            }

            Salesman::where('level', 'manager')->where('area', $oldName)->update(['area' => $newName]);

            return redirect()->route('area.index', ['province' => $newName])
                ->with('success', 'Provinsi dan Kode Provinsi berhasil diperbarui');
        }

        if ($actionType === 'city') {
            $validated = $request->validate([
                'province' => 'required|string|max:255',
                'old_city_name' => 'required|string|max:255',
                'new_city_name' => 'required|string|max:255',
                'city_code' => 'required|string|min:2|max:5',
            ]);

            $prov = $validated['province'];
            $oldName = $validated['old_city_name'];
            $newName = $validated['new_city_name'];
            $newCode = strtoupper($validated['city_code']);

            // Fetch all areas in this city and cascade update names/codes
            $areas = Area::where('province', $prov)->where('city', $oldName)->get();

            foreach ($areas as $a) {
                $parts = explode('-', $a->code);
                $oldAreaCode = $a->code;

                if (count($parts) === 3) {
                    $parts[1] = $newCode;
                    $newAreaCode = implode('-', $parts);
                } elseif (count($parts) === 2) {
                    $parts[0] = $newCode;
                    $newAreaCode = implode('-', $parts);
                } else {
                    $newAreaCode = $a->code;
                }

                $a->city = $newName;
                $a->code = $newAreaCode;
                $a->save();

                if ($oldAreaCode !== $newAreaCode) {
                    Salesman::where('area', $oldAreaCode)->update(['area' => $newAreaCode]);
                    \DB::table('suppliers')->where('area_code', $oldAreaCode)->update(['area_code' => $newAreaCode]);
                }
            }

            Salesman::where('city', $oldName)->update(['city' => $newName]);
            Customer::where('city', $oldName)->update(['city' => $newName]);

            return redirect()->route('area.index', ['province' => $prov, 'city' => $newName])
                ->with('success', 'Kota dan Kode Kota berhasil diperbarui');
        }

        if ($actionType === 'kecamatan') {
            $validated = $request->validate([
                'kecamatan_name' => 'required|string|max:255',
                'kecamatan_code' => 'required|string|min:1|max:5',
            ]);

            $parts = explode('-', $area->code);
            if (count($parts) === 3) {
                $prefix = $parts[0] . '-' . $parts[1] . '-';
            } elseif (count($parts) === 2) {
                $prefix = $parts[0] . '-';
            } else {
                $prefix = '';
            }

            $newCode = strtoupper($prefix . $validated['kecamatan_code']);

            $request->merge(['full_code' => $newCode]);
            $request->validate(['full_code' => 'unique:areas,code,' . $area->id]);

            if ($area->code !== $newCode) {
                Salesman::where('level', 'sales')->where('area', $area->code)->update(['area' => $newCode]);
                \DB::table('suppliers')->where('area_code', $area->code)->update(['area_code' => $newCode]);
            }

            $area->update([
                'name' => $validated['kecamatan_name'],
                'code' => $newCode,
            ]);

            return redirect()->route('area.index', ['province' => $area->province, 'city' => $area->city])
                ->with('success', 'Kecamatan berhasil diupdate');
        }

        return redirect()->route('area.index')->with('error', 'Aksi tidak valid');
    }

    public function destroy(Request $request, Area $area)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Hanya Admin yang diizinkan untuk mengelola wilayah.');

        $actionType = $request->input('action_type');

        if ($actionType === 'province') {
            $provinceName = $request->input('province_name');
            Area::where('province', $provinceName)->delete();
            return redirect()->route('area.index')->with('success', 'Provinsi beserta kota dan kecamatan di dalamnya berhasil dihapus');
        }

        if ($actionType === 'city') {
            $province = $request->input('province');
            $cityName = $request->input('city_name');
            Area::where('province', $province)->where('city', $cityName)->delete();
            return redirect()->route('area.index', ['province' => $province])->with('success', 'Kota beserta kecamatan di dalamnya berhasil dihapus');
        }

        if ($actionType === 'kecamatan' || !$actionType) {
            $prov = $area->province;
            $city = $area->city;
            $area->delete();
            return redirect()->route('area.index', ['province' => $prov, 'city' => $city])->with('success', 'Kecamatan berhasil dihapus');
        }

        return redirect()->route('area.index')->with('error', 'Aksi tidak valid');
    }
}
