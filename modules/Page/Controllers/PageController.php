<?php
namespace Modules\Page\Controllers;

use Modules\AdminController;
use Modules\Page\Models\Page;
use Modules\Core\Models\Terms;
use Modules\Hotel\Models\Hotel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Modules\Page\Models\PageTranslation;

class PageController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $data = [
            'rows' => Page::paginate(20)
        ];

        return view('Page::frontend.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail()
    {
        /**
         * @var Page $page
         * @var PageTranslation $translation
         */
        $slug = request()->route('slug');
        $page = Page::where('slug', $slug)->first();
        if (empty($page) || !$page->is_published) {
            abort(404);
        }
        $translation = $page->translateOrOrigin(app()->getLocale());
        $data = [
            'row' => $page,
            'translation' => $translation,
            'seo_meta'  => $page->getSeoMetaWithTranslation(app()->getLocale(),$translation),
            'body_class'  => "page",
            'hotel_min_max_price' => Hotel::getMinMaxPrice(),
            'terms' => Terms::withCount('hotel')->whereIn('id',json_decode(setting_item('term_properties'))??[] )->orderBy('id')->get(),
        ];
        if (setting_item('term_enable_travel')) {
            $data['travel'] =Terms::withCount('tour')->where('id',1)->whereHas('tour')->first();
        }
        if(!empty($page->header_style) and $page->header_style == "transparent"){
            $data['header_transparent'] = true;
        }
        return view('Page::frontend.detail', $data);
    }
}
