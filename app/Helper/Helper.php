<?php
namespace App\Helper;

use App\Models\Language;
use Illuminate\Support\Facades\File as File;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use \Illuminate\Http\JsonResponse;

class Helper
{

    public static $disk = 'public_uploads';

    static function getCredentials(Request $request): array
    {
    $username = $request->username;
    $credentials =[];
    switch ($username) {
      case filter_var($username, FILTER_VALIDATE_EMAIL):
          $username = 'email';
        break;
      case is_numeric($username):
            $username = 'telephone';
            break;
      default:
           $username = 'email';
        break;
    }
   $credentials[$username] = $request->username;
   $credentials['password'] = $request->password;
   // $credentials['is_active'] = 1;
   return $credentials;
}

    static function responseJson($status,$result, $massage, $data, $status_code): JsonResponse
    {
            $response =
                [
                    'status' => $status,
                    'result' =>$result,
                    'massage' => $massage,
                    'data' => $data

                ];


        return response()->json([$response],(int)$status_code);
    }

    function calculateTotal(): array
    {
        $cart = auth()->user()->cart()->get();

        $subTotals = [];
        $deliveryFees = [];

        foreach ($cart as $cartItem) {


            // Get Price
            $price = $cartItem->meal->price;

            // Get Price Size
            $size_price = $cartItem->size->price;



            // Get prices Options
            if ($cartItem->options != NULL) {

                $options=Cart::getOptions($cartItem->options);
                foreach($options as $option)
                {
                    $option_price=$option->price;
                    $option_qty=$option->qty;
                    $option_prices=$option_price * $option_qty;

                }

            }else {
                $option_prices = 0;
            }

            // Get prices drinks
            if ($cartItem->drinks != NULL) {
                //$drink_prices = collect(Cart::getDrinks($cartItem->drinks))->sum('price');
                $drinks=Cart::getDrinks($cartItem->drinks);
                foreach($drinks as $drink)
                {
                    $drink_price= $drink->price;
                    $drink_qty= $drink->qty;
                    $drink_prices= $drink_price * $drink_qty;


                }

            }else {
                $drink_prices = 0;
            }
//dd($price+$size_price+$option_prices+$drink_prices);

            // Get prices sides
            $the_side_prices=[];

            if ($cartItem->sides != NULL) {
              //  $side_prices = collect(Cart::getSides($cartItem->sides))->sum('price');

              $sides= Cart::getSides($cartItem->sides);
              foreach($sides as $side)
              {

               $side_price=(int)$side->price;

               array_push($the_side_prices,$side_price);

              }

              $side_prices=array_sum($the_side_prices);

                $cart_item_qty = $cartItem->qty;





            } else {
                $side_prices = 0;
            }





            // Get Total Meal Price From "Meal Price , Options, Drinks, Sides"
            $total_meal_price = $price + $size_price + $option_prices + $drink_prices + $side_prices;

            // push into cart Subtotals
            array_push($subTotals, $total_meal_price);
           // dd($subTotals);

            // push delivery fees Item
            array_push($deliveryFees, $cartItem->meal->restaurant->delivery_fees);
            //dd($deliveryFees);
        }

        $subTotal = array_sum($subTotals) * $cart_item_qty;
        $deliveryFees = $deliveryFees[0];
        $total = $subTotal + $deliveryFees;



        return ['subTotal' => $subTotal, 'deliveryFees' => $deliveryFees, 'total' => $total];
    }

    function uploadFile($files, $url = 'files', $key = 'file', $model = null){
  $dist = storage_path('app/public/' . $url);
  if ($url != 'images' && !File::isDirectory(storage_path('app/public/files/' . $url . "/"))){
      File::makeDirectory(storage_path('app/public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR), 0777, true);
      $dist = storage_path('app/public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR);
  }elseif (File::isDirectory(storage_path('app/public/files/' . $url . "/"))) {
      $dist = storage_path('app/public/files/' . $url . "/");
  }
  $file = '';

  if (gettype($files) == 'array') {
    $file = [];
    foreach ($files as $new_file) {
      $file_name = time() . "___file_" . $new_file->getClientOriginalName();
      if ($new_file->move($dist, $file_name)) {
        $file[][$key] = $file_name;
      }
    }
  } else {
    $file = $files;
    $file_name = time() . "___file_" . $file->getClientOriginalName();
    if ($file->move($dist, $file_name)) {
      $file =  $file_name;
    }
  }

  return $file;
}

    public static function returnJson($data): JsonResponse
    {
        return response()->json(['data' => $data]);
    }

    public static function languages($id = null, $local = null)
    {
        $langauge = Language::when($id, function ($query) use ($id){
            $query->where(['id' => $id]);
        })->when($local, function ($query) use ($local){
            $query->where(['local' => $local]);
        })->select(['id','local','name',]);
        return ($id || $local) ? $langauge->first() : $langauge->cursor();
    }

    public static function currentLanguage($local = null)
    {
        $langauge = Language::where(['local' => ($local ?? app()->getLocale())])
            ->select(['id','local','name',])->first();
        return $langauge;
    }

    public static function wordLimit($string, $limit = null, $end = '...'): string
    {
        return Str::words(strip_tags($string), $limit, $end);
    }

    public static function stripText($text, int $limit = null, $stripSpace = false)
    {
        $description = strip_tags(html_entity_decode($text));
        $description = preg_replace('/\s\s+/', ' ', $description);
        if ($limit){
            $description = \Illuminate\Support\Str::limit($description, $limit);
        }
        if ($stripSpace){
            $description = str_replace(' ', '', $description);
        }
        return $description ?? '';
    }

    public static function upload( $file, $folder = null, $oldFile = null, $width= 200, $height= 200)
    {

        $disk = 'public_uploads';
        if(!$file) {
            return null;
        }
        // check mime
        $mime = $file->getMimeType();

        if(!in_array($mime, config('lfm.valid_file_mimetypes'))){
            return ['errors' => __('main.file_not_allow')];
        }

        $path = pathinfo($file->getClientOriginalName());
        $ext = $path['extension']; //$file->extension();
        $name = rand().'_'.time().'.' . $ext;

        // Resize image and upload to Thumbnail path
        $destinationPath = public_path('/uploads/images/thumbs');

//        if ($ext != 'pdf'){
//            $img = \ImageResize::make($file->path());
//
//            $img->resize($width, $height, function ($constraint) {
//                $constraint->aspectRatio(); // to save the ratio
//            })->save($destinationPath.'/'.$name);
//        }

        if ($folder){
            $isFolderExists = Storage::disk(self::$disk)->exists($folder);
            if (!$isFolderExists){
                Storage::disk(self::$disk)->makeDirectory($folder);
            }
        }

        $uploadedFile = $file->storeAs('/'.$folder, $name,['disk' => self::$disk]);

        // delete old file
        if ($oldFile){

            if (file_exists(public_path().'/'.$oldFile)){
                File::delete(public_path().'/'.$oldFile);
            }
            if(Storage::disk(self::$disk)->exists($oldFile)) {
                Storage::disk(self::$disk)->delete($oldFile);
            }
            if(is_file(storage_path(self::$disk."/".$oldFile))){
                File::delete(storage_path(self::$disk."/".$oldFile));
            }
        }

        return 'uploads/images/'.$uploadedFile;
    }

    public static function delete(string $file): bool
    {
        $disk = 'public_uploads';
        if (file_exists(public_path().'/'.$file)){
            File::delete(public_path().'/'.$file);
        }

        if ($file && Storage::disk(self::$disk)->exists($file)) {
            return Storage::disk(self::$disk)->delete($file);
        }
        return false;
    }

    public static function getFullPath($file): string
    {
        return request()->root().'/'.trim($file, '/');
    }


}
