<?php

namespace App\Http\Controllers\Classic\Site;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Website\Theme;
use App\Ogniter\Tools\Strings\Encrypt;
use Illuminate\Http\Request;

class ThemeController extends Controller{

    function changeTheme(Request $request, Theme $themeModel, $theme,$encodedPath){
        $uri = Encrypt::urlBase64Decode($encodedPath);

        if($themeModel->existsByKey($theme)){
            $session = $request->session();

            $session->set('currentTheme', $theme);
        }
        return redirect($uri);
    }

}