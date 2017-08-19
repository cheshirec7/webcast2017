<?php

namespace App\Http\Composers;

use Illuminate\View\View;

/**
 * Class GlobalComposer.
 */
class GlobalComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('logged_in_user', access()->user());

        $sponsors = [];
        array_push($sponsors,'title="Equipedic">Official Saddle Pad<img alt="Equipedic"  src="/img/sponsors/equipedic.gif"');
        array_push($sponsors,'title="Reactor Panel Saddle Company">Official Saddle<img alt="ReactorPanel Saddle Company" src="/img/sponsors/reactor.png"');
        array_push($sponsors,'title="Heritage Gloves">Official Glove<img alt="Heritage Gloves" src="/img/sponsors/heritage.jpg"');
        array_push($sponsors,'title="Echo Valley Ranch">Official Hay &amp;<br/>Feed Store<img alt="Echo Valley Ranch" src="/img/sponsors/echowhite.png"');
        array_push($sponsors,'title="Vettec">Official Farrier<img alt="Vettec" src="/img/sponsors/vettec-wht.png"');
        array_push($sponsors,'title="Kerrits">Official<br/>Rider Apparel<img alt="Kerrits" src="/img/sponsors/kerrits300.png"');
        array_push($sponsors,'title="Redmond Equine">Official Mineral Salt<img alt="Redmond Equine" src="/img/sponsors/redmond-w.png"');
        array_push($sponsors,'title="Taylored Tack">Official Tack<img alt="Taylored Tack" src="/img/sponsors/taylored.png"');
        array_push($sponsors,'title="Freedom">Official Ice Boot<img alt="Freedom" src="/img/sponsors/freedom-w.png"');
        shuffle($sponsors);
        $view->with('sponsors', $sponsors);
    }
}
