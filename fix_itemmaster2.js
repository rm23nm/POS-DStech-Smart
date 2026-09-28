const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/ItemMasterController.php", "utf8");

let target = `       	if ($Scan != "") {
       		$itemmaster->where(DB::raw("CONCAT(itemmaster.KodeItem,' ', itemmaster.NamaItem, ' ', itemmaster.Barcode,' ', merk.NamaMerk)"),'LIKE','%' . $Scan . '%');
       	}

       	$data['data'] = $itemmaster->get();

       	return response()->json($data);`;

let replacement = `       	if ($Scan != "") {
       		$itemmaster->where(DB::raw("CONCAT(itemmaster.KodeItem,' ', itemmaster.NamaItem, ' ', itemmaster.Barcode,' ', merk.NamaMerk)"),'LIKE','%' . $Scan . '%');
       	}

        $KategoriPOS = $request->input('KategoriPOS');
        if ($KategoriPOS != "") {
            $itemmaster->where('itemmaster.KategoriPOS', '=', $KategoriPOS);
        }

       	$data['data'] = $itemmaster->get();

       	return response()->json($data);`;

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/ItemMasterController.php", html);
    console.log("Added KategoriPOS filter to GetStockPerWhs!");
} else {
    console.log("Target not found in GetStockPerWhs!");
}
