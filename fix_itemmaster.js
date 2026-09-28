const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/ItemMasterController.php", "utf8");

let target = `$oItem = new ItemMaster();
      $itemmaster = $oItem->GetItemData(Auth::user()->RecordOwnerID,$KodeJenis, $Merk, $TipeItem,$TipeItemIN, $Active, $Scan,1);

      $data['data'] = $itemmaster->get();`;

let replacement = `$oItem = new ItemMaster();
      $itemmaster = $oItem->GetItemData(Auth::user()->RecordOwnerID,$KodeJenis, $Merk, $TipeItem,$TipeItemIN, $Active, $Scan,1);

      $KategoriPOS = $request->input('KategoriPOS');
      if ($KategoriPOS != "") {
          $itemmaster->where('itemmaster.KategoriPOS', '=', $KategoriPOS);
      }

      $data['data'] = $itemmaster->get();`;

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/ItemMasterController.php", html);
    console.log("Added KategoriPOS filter to ViewJson!");
} else {
    console.log("Target not found in ViewJson!");
}
