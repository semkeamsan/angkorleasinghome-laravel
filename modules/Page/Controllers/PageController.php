<?php
namespace Modules\Page\Controllers;

use Modules\AdminController;
use Modules\Page\Models\Page;
use Modules\Core\Models\Terms;
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
            'terms' => Terms::withCount('hotel')->whereIn('id',[37,36,95,97])->orderBy('id')->get(),
            'travel' => Terms::withCount('tour')->where('id',1)->whereHas('tour')->first(),
        ];
        if(!empty($page->header_style) and $page->header_style == "transparent"){
            $data['header_transparent'] = true;
        }
        return view('Page::frontend.detail', $data);
    }
}
