@extends ('frontend.layouts.app')
@section ('title', 'Checkpoint List')
@section('page-header')
    <h1>Checkpoint List</h1>
@endsection
@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>Checkpoint</th>
                <th>Mileage</th>
                <th>From Soda Springs</th>
                <th>To Auburn</th>
                <th>Check Type</th>
                <th>Crew</th>
                <th>Pulse</th>
                <th>Cut-Off Times</th>
                <th>Cut-Off Guidelines</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Soda Springs</td>
                <td>0.0</td>
                <td>0.0</td>
                <td>100.0</td>
                <td>Ride Start at 5:15 am</td>
                <td>Yes</td>
                <td>&nbsp;</td>
                <td>5:30 am OUT</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Road 51</td>
                <td>12.0</td>
                <td>12.0</td>
                <td>88.0</td>
                <td>Trot By (Vet available)</td>
                <td>NO</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>6:45 am IN</td>
            </tr>
            <tr>
                <td>Lyon Ridge</td>
                <td>2.5</td>
                <td>14.5</td>
                <td>85.5</td>
                <td>Radio Checkpoint</td>
                <td>NO</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Red Star Ridge</td>
                <td>5.5</td>
                <td>20.0</td>
                <td>80.0</td>
                <td>Gate &amp; Go ++</td>
                <td>NO</td>
                <td>60</td>
                <td>&nbsp;</td>
                <td>8:00 am IN</td>
            </tr>
            <tr>
                <td>Robinson Flat</td>
                <td>16.0</td>
                <td>36.0</td>
                <td>64.0</td>
                <td>Gate to Hold: 1 Hr.</td>
                <td>Yes (1)</td>
                <td>60</td>
                <td>12:00 pm IN 1:15 pm OUT</td>
                <td>11:00 am IN</td>
            </tr>
            <tr>
                <td>Dusty Corners</td>
                <td>9.0</td>
                <td>45.0</td>
                <td>55.0</td>
                <td>Water Only (No Check)</td>
                <td>Ok (2)</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Last Chance</td>
                <td>5.0</td>
                <td>50.0</td>
                <td>50.0</td>
                <td>Gate &amp; Go ++</td>
                <td>NO</td>
                <td>64</td>
                <td>3:15 pm IN</td>
                <td>3:00 pm IN</td>
            </tr>
            <tr>
                <td>Devil’s Thumb</td>
                <td>4.0</td>
                <td>54.0</td>
                <td>46.0</td>
                <td>Water only (No Check)</td>
                <td>NO</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Deadwood</td>
                <td>1.0</td>
                <td>55.0</td>
                <td>45.0</td>
                <td>Gate &amp; Go ++</td>
                <td>NO</td>
                <td>64</td>
                <td>5:00 pm IN</td>
                <td>4:30 pm IN</td>
            </tr>
            <tr>
                <td>Michigan Bluff</td>
                <td>7.5</td>
                <td>62.5</td>
                <td>37.5</td>
                <td>Water Only (No Check)</td>
                <td>Yes (3)</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>6:15 pm IN</td>
            </tr>
            <tr>
                <td>Pieper Junction</td>
                <td>1.5</td>
                <td>64.0</td>
                <td>36.0</td>
                <td>Gate and Go ++</td>
                <td>Yes (4)</td>
                <td>64</td>
                <td></td>
                <td>7:30 pm IN</td>
            </tr>
            <tr>
                <td>Foresthill</td>
                <td>4.0</td>
                <td>68.0</td>
                <td>32.0</td>
                <td>Gate to Hold: 1 Hr.</td>
                <td>Yes</td>
                <td>64</td>
                <td>8:30 pm IN 9:45 pm OUT</td>
                <td>8:00 pm IN</td>
            </tr>
            <tr>
                <td>Cal 2</td>
                <td>10.0</td>
                <td>78.0</td>
                <td>22.0</td>
                <td>Hay & Water (No Check)</td>
                <td>NO</td>
                <td></td>
                <td></td>
                <td>11:45 pm IN</td>
            </tr>
            <tr>
                <td>Francisco’s</td>
                <td>7.0</td>
                <td>85.0</td>
                <td>15.0</td>
                <td>Gate &amp; Go ++</td>
                <td>NO</td>
                <td>64</td>
                <td>1:45 am IN</td>
                <td>1:00 am IN</td>
            </tr>
            <tr>
                <td>River Crossing</td>
                <td>3.3</td>
                <td>88.0</td>
                <td>12.0</td>
                <td>(No Check)</td>
                <td>NO</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Lower Quarry</td>
                <td>6.0</td>
                <td>94.0</td>
                <td>6.0</td>
                <td>Gate &amp; Go ++</td>
                <td>NO</td>
                <td>64</td>
                <td>3:45 am IN 4:00 am OUT</td>
                <td>3:30 am IN</td>
            </tr>
            <tr>
                <td>No Hands Bridge</td>
                <td>2.0</td>
                <td>96.0</td>
                <td>4.0</td>
                <td>(No Check)</td>
                <td>Ok (2)</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Auburn Staging Area</td>
                <td>4.0</td>
                <td>100.0</td>
                <td>0.0</td>
                <td>Timed Finish</td>
                <td>No</td>
                <td>&nbsp;</td>
                <td>5:15 am</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>McCann Stadium</td>
                <td>0.1</td>
                <td>100.1</td>
                <td>&nbsp;</td>
                <td>“Fit to Continue” Vet Release Examination</td>
                <td>Yes</td>
                <td>64 / Sound</td>
                <td colspan="2">40 minutes to reach pulse at McCann</td>
            </tr>
            </tbody>
        </table>
        <p style="margin: 10px 0 20px;"><i>“Gate” = when criteria reached, present horse. Criteria must be met within 30 min. of arrival ++After cut off Riders must leave 10 min. after vetting (1) Limit
                of one vehicle per rider; pass required. (2) Crews allowed, but not recommended. (3) Park short of Chicken Hawk Road and walk down to Michigan. (4) Walk
                in from Chicken Hawk Road. (5) This mandatory vet exam, between 1 & 2 hours after McCann finish, does not affect finish status; vets want to assure
                themselves that all horses are okay. Haggin Cup exams are held on Sunday at 10:00 am–All First Ten Horses MUST remain at the Fairgrounds (See
                Rule 11) 7/17/2017</i></p>
    </div>
    <img class="img-thumbnail img-responsive" src="/img/ttpart1.jpg">
    <img class="img-thumbnail img-responsive" src="/img/ttpart2.jpg">
    <img class="img-thumbnail img-responsive" src="/img/ttpart3.jpg">
@endsection
