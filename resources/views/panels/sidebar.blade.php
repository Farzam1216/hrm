@php
$configData = Helper::applClasses();

@endphp
<div class="main-menu menu-fixed {{($configData['theme'] === 'dark') ? 'menu-dark' : 'menu-light'}} menu-accordion menu-shadow" data-scroll-to-active="true">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item mr-auto">
        <a class="navbar-brand" href="{{url('/')}}">
          <span class="brand-logo">
            <img src="{{asset('images/ico/glowlogixpng.png')}}" height="27px"/>
          </span>
          <h2 class="brand-text">GleamHR</h2>
        </a>
      </li>
      <li class="nav-item nav-toggle">
        <a class="d-flex align-items-center modern-nav-toggle pr-0" data-toggle="collapse">
          <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
          <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i>
        </a>
      </li>
    </ul>
  </div>
  <div class="shadow-bottom"></div>
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->

      <li class="nav-item @if(request()->is($locale.'/dashboard')) active @endif">
        <a class="d-flex align-items-center" href="@if(isset($locale)){{url($locale.'/dashboard')}} @else {{url('en/dashboard')}} @endif">
          <i data-feather="home"></i>
          <span class="menu-title text-truncate">{{ __('language.Dashboard') }}</span>
        </a>
      </li>

      <li class="nav-item">
        {{-- Need to verify request --}}
        <a class="d-flex align-items-center @if(request()->is('/*/dashboard')) @endif" href="@if(isset($locale)){{url($locale.'/inbox')}} @else {{url('en/inbox')}} @endif">
          <i data-feather="mail"></i>
          <span class="menu-title text-truncate">{{ __('language.Inbox') }}</span>
          <span class="badge badge-pill badge-light-primary ml-auto mr-1">{{$notificationCount}}</span>
        </a>
      </li>
      @if ( $isAdmin || isset($menu['hiring']))
      <li class="nav-item has-sub @if(str_contains( Request::fullUrl(),$locale.'/candidates') || str_contains( Request::fullUrl(),$locale.'/candidate') || str_contains( Request::fullUrl(),$locale.'/job'))   @endif">
        <a href="javascript:void(0)" class="d-flex align-items-center">
          <i data-feather="layers"></i>
          <span class="menu-title text-truncate">{{ __('language.Hiring') }}</span>
        </a>
        <ul class="menu-content">
          <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/candidates') || str_contains( Request::fullUrl(),$locale.'/candidate')) active @endif">

            <a href="@if(isset($locale)){{url($locale.'/candidates')}}
            @else {{url('en/candidates')}} @endif" class="d-flex align-items-center">
            <i data-feather="circle"></i>
            <span class="menu-title text-truncate">{{__('language.Applicants')}}</span>
          </a>
        </li>
        <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/job')) active @endif">
          <a href="@if(isset($locale)){{url($locale.'/job')}}
          @else {{url('en/job')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Job')}} {{__('language.Openings')}}</span>
        </a>
      </li>
    </ul>
  </li>
  @endif
  @if ( $isAdmin || isset($menu['my-info']) )
  <li class="nav-item has-sub @if(str_contains( Request::fullUrl(),$locale.'/employee/docs/'.Auth::user()->id)
    || request()->is($locale.'/employee/edit/'.Auth::user()->id)
    || request()->is($locale.'/employee/kill/'.Auth::user()->id)
    || request()->is($locale.'/employee/restore/'.Auth::user()->id)
    || request()->is($locale.'/employee/delete/'.Auth::user()->id)
    || str_contains( Request::fullUrl(),$locale.'/employee/'.Auth::user()->id.'/timeoff')
    || str_contains( Request::fullUrl(),$locale.'/employee/asset/'.Auth::user()->id)
    || str_contains( Request::fullUrl(),$locale.'/employees/'.Auth::id().'/benefit-details')
    || str_contains( Request::fullUrl(),$locale.'/employee/benefit-status/'.Auth::id().'-')
    || str_contains( Request::fullUrl(),$locale.'/employees/'.Auth::id().'/task')
    || str_contains( Request::fullUrl(),$locale.'/employees/'.Auth::id().'/dependents'))   @endif
    ">
    <a href="javascript:void(0)" class="d-flex align-items-center">
      <i data-feather="user"></i>
      <span>
        {{__('language.My Info')}}
      </span>
    </a>
    <ul class="menu-content">
      @if ( $isAdmin || isset($menu['my-info']['personal']) )
      <li class="nav-item @if(request()->is($locale.'/employee/edit/'.Auth::user()->id)
        || request()->is($locale.'/employee/kill/'.Auth::user()->id)
        || request()->is($locale.'/employee/restore/'.Auth::user()->id)
        || request()->is($locale.'/employee/delete/'.Auth::user()->id)) active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employee/edit/'.Auth::user()->id)}} @else {{url('en/employee/edit/'.Auth::user()->id)}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Personal')}}</span>
        </a>
      </li>
      @endif
      @if ( $isAdmin || isset($menu['my-info']['documents']) )
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employees/'.Auth::user()->id.'/docs')) active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employees/'.Auth::user()->id.'/docs')}} @else {{url('en/employees/'.session('unauthorized_user')->id.'/docs')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Documents')}}</span>
        </a>
      </li>
      @endif
      @if ( $isAdmin || isset($menu['my-info']['tasks']) )
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employees/'.Auth::id().'/task')) active @endif">
        <a href="#" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.My Task')}}</span>
        </a>
      </li>
      @endif
      @if ($isAdmin || isset($menu['my-info']['time-off']))
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employee/'.Auth::id().'/timeoff') || str_contains( Request::fullUrl(),$locale.'/employee/'.Auth::user()->id.'/timeoff')) active @endif">
        <a href="@if(isset($locale)){{route('timeoff.index', [$locale, Auth::id()])}} @else {{route('timeoff.index', ['en', Auth::id()])}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Time Off')}}</span>
        </a>
      </li>
      @endif
      @if ($isAdmin || isset($menu['my-info']['benefits']))
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employees/'.Auth::id().'/benefit-details') || str_contains( Request::fullUrl(),$locale.'/employee/benefit-status/'.Auth::id().'-')) active @endif">
        <a href="#" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Benefits')}}</span>
        </a>
      </li>
      @endif
      @if ( $isAdmin ||
      isset($menu['my-info']['dependents'])
      )
      <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/employees/'.Auth::id().'/dependents')) active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employees/'.Auth::id().'/dependents')}} @else {{url('en/employees/'.Auth::id().'/dependents')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Dependent')}}</span>
        </a>
      </li>
      @endif
      @if ($isAdmin || (isset($menu['my-info']['attendance']) && Auth::user()->can_mark_attendance == 1))
      <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/employees/'.Auth::user()->id.'/employee-attendance')) active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employees/'.Auth::user()->id.'/employee-attendance')}} @else {{url('en/employees/'.Auth::user()->id.'/employee-attendance')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.My Attendance')}}</span>
        </a>
      </li>
      @endif
    </ul>
  </li>
  @endif
  @if(session()->has('unauthorized_user'))
  @if($isAdmin || isset($menu['unAuthUser']))
  <li class="nav-item has-sub @if(str_contains( Request::fullUrl(),$locale.'/employee/docs/'.session('unauthorized_user')->id)
    || request()->is($locale.'/employee/edit/'.session('unauthorized_user')->id)
    || request()->is($locale.'/employee/kill/'.session('unauthorized_user')->id)
    || request()->is($locale.'/employee/restore/'.session('unauthorized_user')->id)
    || request()->is($locale.'/employees/'.session('unauthorized_user')->id.'/employee-attendance')
    || request()->is($locale.'/employees/'.session('unauthorized_user')->id.'/employee-attendance')
    || request()->is($locale.'/employee/delete/'.session('unauthorized_user')->id)
    || str_contains( Request::fullUrl(),$locale.'/employee/'.session('unauthorized_user')->id.'/timeoff')
    || str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/assets')
    || str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/benefit-details')
    || str_contains( Request::fullUrl(),$locale.'/employee/benefit-status/'.Auth::id().'-')
    || str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/task')
    || str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/employee-attendance')
    || str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/dependents'))  @endif ">
    <a href="javascript:void(0)" class="d-flex align-items-center ">
      <i data-feather='user-check'></i>
      <span>{{session('unauthorized_user')->firstname}} {{session('unauthorized_user')->lastname}}
        {{-- <i class="fas fa-angle-left right"></i>--}}
      </span>
    </a>
    <ul class="menu-content">
      @if($isAdmin || isset($menu['unAuthUser']['personal']))
      <li class="nav-item @if(request()->is($locale.'/employee/edit/'.session('unauthorized_user')->id)
        || request()->is($locale.'/employee/kill/'.session('unauthorized_user')->id)
        || request()->is($locale.'/employee/restore/'.session('unauthorized_user')->id)
        || request()->is($locale.'/employee/delete/'.session('unauthorized_user')->id)) active @endif" aria-haspopup="true"><a href="@if(isset($locale)){{url($locale.'/employee/edit/'.session('unauthorized_user')->id)}} @else {{url('en/employee/edit/'.session('unauthorized_user')->id)}} @endif" class="d-flex align-items-center"><i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Personal')}}</span>
        </a>
      </li>
      @endif
      @if($isAdmin || isset($menu['unAuthUser']['documents']))
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/docs')) active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employees/'.session('unauthorized_user')->id.'/docs')}} @else {{url('en/employees/'.session('unauthorized_user')->id.'/docs')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Documents')}}</span>
        </a>
      </li>
      @endif
      @if($isAdmin || isset($menu['unAuthUser']['assets']))
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/assets')) active @endif" aria-haspopup="true">
        <a href="@if(isset($locale)){{url($locale.'/employees/'.session('unauthorized_user')->id.'/assets')}} @else {{url('en/employees/'.session('unauthorized_user')->id.'/assets')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Assets')}}</span>
        </a>
      </li>
      @endif
      @if($isAdmin || isset($menu['unAuthUser']['tasks']))
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/task')) active @endif">
        <a href="#" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Tasks')}}</span>
        </a>
      </li>
      @endif
      @if($isAdmin || isset($menu['unAuthUser']['time-off']))
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employee/'.session('unauthorized_user')->id.'/timeoff') || str_contains( Request::fullUrl(),$locale.'/employee/'.session('unauthorized_user')->id.'/timeoff')) active @endif">
        <a href="@if(isset($locale)){{route('timeoff.index', [$locale, session('unauthorized_user')->id])}} @else {{route('timeoff.index', ['en', session('unauthorized_user')->id])}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Time Off')}}</span>
        </a>
      </li>
      @endif
      @if($isAdmin || isset($menu['unAuthUser']['benefits']))
      <li class="nav-item @if(str_contains( Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/benefit-details') || str_contains( Request::fullUrl(),$locale.'/employee/benefit-status/'.session('unauthorized_user')->id.'-')) active @endif">
        <a href="#" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Benefits')}}</span>
        </a>
      </li>
      @endif

      @if($isAdmin || isset($menu['unAuthUser']['dependents']))
      <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/dependents')) active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employees/'.session('unauthorized_user')->id.'/dependents')}} @else {{url('en/employees/'.session('unauthorized_user')->id.'/dependents')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Dependent')}}</span>
        </a>
      </li>
      @endif
      @if($isAdmin || isset($menu['unAuthUser']['attendance']))
      <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/employees/'.session('unauthorized_user')->id.'/employee-attendance') || str_contains( Request::fullUrl(),$locale.'/attendance/import')) active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employees/'.session('unauthorized_user')->id.'/employee-attendance')}} @else {{url('en/employees/'.session('unauthorized_user')->id.'/employee-attendance')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">{{__('language.Attendance')}}</span>
        </a>
      </li>
      @endif
    </ul>
  </li>
  @endif
  @endif
  <li class="nav-item has-sub @if( request()->is($locale.'/employees')
    || str_contains( Request::fullUrl(),$locale.'/organization-hierarchy')
    || request()->is($locale.'/employee/trashed')
    || request()->is($locale.'/employee/create') || request()->is($locale.'/employee/import/create') || request()->is($locale.'/employee/import/preview'))   @endif">
    <a href="javascript:void(0)" class="d-flex align-items-center">
      <i data-feather="users"></i>
      <span class="menu-title text-truncate">
        {{__('language.People Management')}}
      </span>
    </a>
    <ul class="menu-content">
      <li class="@if(request()->is($locale.'/employees') || request()->is($locale.'/employee/trashed')
        || request()->is($locale.'/employee/create') 
        || request()->is($locale.'/employee/import/create') 
        || request()->is($locale.'/employee/import/preview') )active @endif">
        <a href="@if(isset($locale)){{url($locale.'/employees')}} @else {{url('en/employees')}} @endif" class="d-flex align-items-center">
          <i data-feather="circle"></i>
          <span class="menu-title text-truncate">
            {{__('language.Employees')}}
          </span> </a>
        </li>
        <li class="@if(str_contains( Request::fullUrl(),$locale.'/organization-hierarchy')) active @endif">
          <a href="#" class="d-flex align-items-center">
            <i data-feather="circle"></i>
            <span class="menu-title text-truncate">{{__('language.Org Chart')}}</span>

          </a>
        </li>
      </ul>
    </li>

<li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/asset_types')
|| str_contains(Request::fullUrl(),$locale.'/education-types')
|| str_contains(Request::fullUrl(),$locale.'/visa-types')
)     @endif" target="_self">
<a href="javascript:void(0)" class="d-flex align-items-center">
  <i data-feather='clock'></i>
  <span class="menu-title text-truncate">
    {{__('language.Attendance Management')}}
  </span>
</a>

<ul class="menu-content">
  @if ($isAdmin || isset($menu['attendance']))
    <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/attendance-management')) active @endif">
      <a href="@if(isset($locale)){{url($locale.'/attendance-management')}} @else {{url('en/attendance-management')}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Employees Attendance')}}</span>
      </a>
    </li>
  @endif
  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/correction-requests')) active @endif">
    <a href="@if(isset($locale)){{route('correction-requests.index', [$locale])}} @else {{route('correction-requests.index', ['en'])}} @endif" class="d-flex align-items-center">
      <i data-feather="circle"></i>
      <span class="menu-title text-truncate">{{__('language.Correction Requests')}}</span>
    </a>
  </li>
</ul>
</li>
@if ($isAdmin || isset($menu['payroll']) || isset($menu['paySchedule']))
<li class="has-sub @if(str_contains(Request::fullUrl(),$locale.'/payroll-management')
|| str_contains(Request::fullUrl(),$locale.'/pay-schedule')
|| str_contains(Request::fullUrl(),$locale.'/visa-types')
)     @endif" target="_self">
<a href="javascript:void(0)" class="d-flex align-items-center">
  <i data-feather="dollar-sign"></i>
  <span class="menu-title text-truncate">
    {{__('language.Payroll Management')}}
  </span>
</a>

<ul class="menu-content"> 
  @if ($isAdmin || isset($menu['payroll']))
    <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/payroll-management')) active @endif">
      <a href="@if(isset($locale)){{url($locale.'/payroll-management')}} @else {{url('en/payroll-management')}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Payrolls')}}</span>
      </a>
    </li>
  @endif
  @if ($isAdmin || isset($menu['paySchedule']))
    <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/pay-schedule')) active @endif">
      <a href="@if(isset($locale)){{route('pay-schedule.index', [$locale])}} @else {{route('pay-schedule.index', ['en'])}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Pay Schedules')}}</span>
      </a>
    </li>
  @endif
  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/payroll-history')) active @endif">
    <a href="@if(isset($locale)){{url($locale.'/payroll-history')}} @else {{url('en/payroll-history')}} @endif" class="d-flex align-items-center">
      <i data-feather="circle"></i>
      <span class="menu-title text-truncate">{{__('language.Payrolls History')}}</span>
    </a>
  </li>
</ul>
</li>
@endif
<li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/polls'))   @endif">
  <a href="javascript:void(0)" class="d-flex align-items-center">
    <i data-feather="list"></i>
    <span class="menu-title text-truncate">
      {{__('language.Engagement Survey')}}
    </span>
  </a>
  <ul class="menu-content ">
    <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/polls')) active  @endif">
      <a href="@if(isset($locale)){{route('polls.index', [$locale])}} @else {{route('polls.index', ['en'])}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Polls')}}</span>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/performance-review'))   @endif">
  <a href="javascript:void(0)" class="d-flex align-items-center">
    <i data-feather='file-text'></i>
    <span class="menu-title text-truncate">
      {{__('language.Performance Review')}}
    </span>
  </a>
  <ul class="menu-content ">   
    @if ($isAdmin || isset($menu['performance']))
    <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/performance-review/questions')) active  @endif">
      <a href="@if(isset($locale)){{route('questions.index', [$locale])}} @else {{route('questions.index', ['en'])}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Questions')}}</span>
      </a>
    </li>
    <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/performance-review/forms')) active  @endif">
      <a href="@if(isset($locale)){{route('forms.index', [$locale])}} @else {{route('forms.index', ['en'])}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Forms')}}</span>
      </a>
    </li>
    @endif
    <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/performance-review/evaluations')) active  @endif">
      <a href="@if(isset($locale)){{route('evaluations.index', [$locale])}} @else {{route('evaluations.index', ['en'])}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Evaluations')}}</span>
      </a>
    </li>
  </ul>
</li>
@if ($isAdmin)
<li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/access-level'))   @endif">
  <a href="javascript:void(0)" class="d-flex align-items-center">
    <i data-feather="lock"></i>
    <span class="menu-title text-truncate">
      {{__('language.Manage Roles')}}
    </span>
  </a>
  <ul class="menu-content ">
    <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/access-level')) active  @endif">
      <a href="@if(isset($locale)){{url($locale.'/access-level')}} @else {{url('en/access-level')}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Access Levels')}}</span>
      </a>
    </li>
  </ul>
</li>
@endif
<li class="nav-item has-sub">
  <a href="javascript:void(0)" class="d-flex align-items-center">
    <i data-feather='book-open'></i>
    <span class="menu-title text-truncate">
      {{__('language.Company')}}
    </span>
  </a>
  <ul class="menu-content">
    <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/handbook/chapter') || str_contains(Request::fullUrl(),$locale.'/handbook/page')) active @endif">
      <a href="@if(isset($locale)){{route('chapter.index', [$locale])}} @else {{route('chapter.index', ['en'])}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Handbook')}}</span>
      </a>
    </li>
    <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/holidays')) active @endif">
      <a href="@if(isset($locale)){{route('holidays.index', [$locale])}} @else {{route('holidays.index', ['en'])}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Holidays')}}</span>
      </a>
    </li>
  </ul>
</li>
      <li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/asset_types')
    || str_contains(Request::fullUrl(),$locale.'/education-types')
    || str_contains(Request::fullUrl(),$locale.'/visa-types')
    || str_contains(Request::fullUrl(),'documents')
    || str_contains(Request::fullUrl(),$locale.'/doc-type')
    || str_contains(Request::fullUrl(),$locale.'/task')
    || str_contains(Request::fullUrl(),$locale.'/taskcategory')
    || request()->is($locale.'/delete/task_template/document')
    || str_contains(Request::fullUrl(),$locale.'/branch')
    || str_contains(Request::fullUrl(),$locale.'/department')
    || str_contains(Request::fullUrl(),$locale.'/employmentstatus')
    || str_contains(Request::fullUrl(),$locale.'/designations')
    || str_contains(Request::fullUrl(),$locale.'/designations')
    || str_contains(Request::fullUrl(),$locale.'/division')
    || str_contains(Request::fullUrl(),$locale.'/benefit-plan')
    || str_contains(Request::fullUrl(),$locale.'/benefitgroup')
    || str_contains(Request::fullUrl(),$locale.'/benefitgroupplan')
    || str_contains(Request::fullUrl(),$locale.'/timeoff')
    || str_contains(Request::fullUrl(),$locale.'/timeofftype')
    || str_contains(Request::fullUrl(),$locale.'/question')
    || str_contains(Request::fullUrl(),$locale.'/questiontype')
    || str_contains(Request::fullUrl(),$locale.'/email-templates')
    || str_contains(Request::fullUrl(),$locale.'/secondarylanguage')
    || str_contains(Request::fullUrl(),$locale.'/language')
    || str_contains(Request::fullUrl(),$locale.'/approvals')
    || str_contains(Request::fullUrl(),$locale.'/approval-workflows')
    || request()->is($locale.'/leave-types')
    || request()->is($locale.'/importdata')
    || str_contains(Request::fullUrl(),$locale.'/smtp-details')
    )    @endif">
        <a href="javascript:void(0)" id="settingsoption" class="d-flex align-items-center">
          <i data-feather="settings"></i>
          <span class="menu-title text-truncate">
        {{__('language.Settings')}}
      </span>
        </a>
        <ul class="menu-content" id="setheight">
          @if ($isAdmin || isset($menu['settings']['education type']) || isset($menu['settings']['asset type']) || isset($menu['settings']['visa type']))
            <li class="has-sub @if(str_contains(Request::fullUrl(),$locale.'/asset_types')
      || str_contains(Request::fullUrl(),$locale.'/education-types')
      || str_contains(Request::fullUrl(),$locale.'/visa-types')
      )     @endif" target="_self">
              <a href="javascript:void(0)" class="d-flex align-items-center">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
          {{__('language.Types')}}
        </span>
              </a>

              <ul class="menu-content">
                @if($isAdmin || isset($menu['settings']['education type']))
                  <li class="nav-item  @if(str_contains(Request::fullUrl(),$locale.'/education-types')) active @endif">
                    <a href="@if(isset($locale)){{url($locale.'/education-types')}} @else {{url('en/education-types')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Education')}} {{__('language.Type')}}</span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['asset type']))
                  <li class="nav-item  @if(str_contains(Request::fullUrl(),$locale.'/asset-types')) active @endif">
                    <a href="@if(isset($locale)){{url($locale.'/asset-types')}} @else {{url('en/asset-types')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Asset')}} {{__('language.Types')}}</span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['visa type']))
                  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/visa-types')) active @endif">
                    <a href="@if(isset($locale)){{url($locale.'/visa-types')}} @else {{url('en/visa-types')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('Visa Type')}}</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif
          @if ($isAdmin || isset($menu['attendance']) || isset($menu['workShedule']))
            <li class="has-sub @if(str_contains(Request::fullUrl(),$locale.'/asset_types')
    || str_contains(Request::fullUrl(),$locale.'/education-types')
    || str_contains(Request::fullUrl(),$locale.'/visa-types')
    )     @endif" target="_self">
              <a href="javascript:void(0)" class="d-flex align-items-center">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
        {{__('language.Attendance')}}
      </span>
              </a>

              <ul class="menu-content">
                @if($isAdmin || isset($menu['workShedule']))
                    <li class="nav-item  @if(str_contains(Request::fullUrl(),$locale.'/work-schedule') || str_contains(Request::fullUrl(),$locale.'/assign/work-schedule')) active @endif">
                      <a
                              href="@if(isset($locale)){{url($locale.'/work-schedule')}} @else {{url('en/work-schedule')}} @endif"
                              class="d-flex align-items-center">
                        <span class="menu-title text-truncate">{{__('language.Work')}} {{__('language.Schedule')}}</span>
                      </a>
                    </li>
                @endif
                @if($isAdmin || isset($menu['attendance']))
                <li class="nav-item  @if(str_contains(Request::fullUrl(),$locale.'/employee-attendance-approval') || str_contains(Request::fullUrl(),$locale.'/employee-attendance-approval')) active @endif">
                  <a href="@if(isset($locale)){{url($locale.'/employee-attendance-approval')}} @else {{url('en/employee-attendance-approval')}} @endif"
                     class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Attendance')}} {{__('language.Approval')}}</span>
                  </a>
                </li>  
                @endif
              </ul>
            </li>
          @endif
          @if($isAdmin || isset($menu['settings']['document']) || isset($menu['settings']['document type']))
            <li class="nav-item has-sub @if(str_contains(Request::fullUrl(),'documents') || str_contains(Request::fullUrl(),$locale.'/doc-types'))   @endif">
              <a href="javascript:void(0)" class="d-flex align-items-center">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
                  {{__('language.Documents')}}
                </span>
              </a>

              <ul class="menu-content">
                @if($isAdmin || isset($menu['settings']['document']))
                  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/documents')) active @endif ">
                    <a href="@if(isset($locale)){{url($locale.'/documents')}} @else {{url('en/documents')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Documents')}}</span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['document type']))
                  <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/doc-types')) active @endif">
                    <a href="@if(isset($locale)){{url($locale.'/doc-types')}} @else {{url('en/doc-types')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Document Types')}}</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif
          @if ($isAdmin || isset($menu['settings']['tasks']))
            <li class="has-sub @if(str_contains(Request::fullUrl(),$locale.'/tasks') || str_contains(Request::fullUrl(),$locale.'/taskcategory') || request()->is($locale.'/delete/task_template/document'))  @endif">
              <a href="javascript:void(0)" class="d-flex align-items-center">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
                  {{__('language.Onboarding')}}
                </span>
              </a>
              <ul class="menu-content">
                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/onboarding-categories')) active @endif" aria-haspopup="true"><a href="@if(isset($locale)) {{url($locale.'/onboarding-categories')}} @else {{url('en/onboarding-categories')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Categories')}}</span>
                  </a>
                </li>
                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/onboarding-tasks') || request()->is($locale.'/delete/task_template/document')) active @endif" aria-haspopup="true">
                  <a href="@if(isset($locale)) {{url($locale.'/onboarding-tasks')}} @else {{url('en/onboarding-tasks')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Tasks')}}</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="has-sub">
              <a href="javascript:void(0)" class="d-flex align-items-center">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
                  {{__('language.Offboarding')}}
                </span>
              </a>
              <ul class="menu-content">
                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/offboarding-categories')) active @endif" aria-haspopup="true"><a href="@if(isset($locale)) {{url($locale.'/offboarding-categories')}} @else {{url('/offboarding-categories')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Categories')}}</span>
                  </a>
                </li>
                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/offboarding-tasks') || request()->is($locale.'/delete/task_template/document')) active @endif" aria-haspopup="true">
                  <a href="@if(isset($locale)) {{url($locale.'/offboarding-tasks')}} @else {{url('en/offboarding-tasks')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Tasks')}}</span>
                  </a>
                </li>
              </ul>
            </li>
          @endif
          @if($isAdmin || isset($menu['settings']['location']) || isset($menu['settings']['department']) || isset($menu['settings']['employment status']) || isset($menu['settings']['designation']) || isset($menu['settings']['division']))
            <li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/branch')
            || str_contains(Request::fullUrl(),$locale.'/department')
            || str_contains(Request::fullUrl(),$locale.'/employmentstatus')
            || str_contains(Request::fullUrl(),$locale.'/designations')
            || str_contains(Request::fullUrl(),$locale.'/designations')
            || str_contains(Request::fullUrl(),$locale.'/division'))  @endif">
              <a href="javascript:void(0)" class="d-flex align-items-center ">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
                  {{__('language.Job')}}
                </span>
              </a>
              <ul class="menu-content">
                @if($isAdmin || isset($menu['settings']['location']))
                  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/locations')) active @endif">
                    <a href="@if(isset($locale)){{url($locale.'/locations')}} @else {{url('en/locations')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate"> {{__('language.Location')}}</span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['department']))
                  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/departments')) active @endif ">
                    <a href="@if(isset($locale)){{url($locale.'/departments')}} @else {{url('en/departments')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Departments')}}</span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['employment status']))
                  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/employment-status')) active @endif">
                    <a href="@if(isset($locale)){{url($locale.'/employment-status')}} @else {{url('en/employment-status')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Employment Status')}} </span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['designation']))
                  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/designations')) active  @endif">
                    <a href="@if(isset($locale)){{url($locale.'/designations')}} @else {{url('en/designations')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Designations')}}</span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['division']))
                  <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/divisions')) active @endif">
                    <a href="@if(isset($locale)){{url($locale.'/divisions')}} @else {{url('en/divisions')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Division')}}</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif
          {{-- Benefits Submenu--}}
          @if ( $isAdmin || isset($menu['settings']['benefits']))
            <li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/benefit-plan')
            || str_contains(Request::fullUrl(),$locale.'/benefitgroup')
            || str_contains(Request::fullUrl(),$locale.'/benefitgroupplan'))  @endif">
              <a href="javascript:void(0)" class="d-flex align-items-center ">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
                  {{__('language.Benefits')}}
                </span>
              </a>
              <ul class="menu-content" id="setbenefitheight">

                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/benefit-plan')) active @endif" aria-haspopup="true">
                  <a href="@if (isset($locale) ){{url($locale.'/benefit-plan')}} @else {{url($locale.'/benefit-plan')}}@endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Plans')}}</span>
                  </a>
                </li>

                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/benefitgroup') || str_contains(Request::fullUrl(),$locale.'/benefitgroupplan')) active @endif" aria-haspopup="true">
                  <a href="@if(isset($locale)){{url($locale.'/benefitgroup')}} @else {{url('en/benefitgroup')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Benefit Groups')}}</span>
                  </a>
                </li>
              </ul>
            </li>
          @endif
          @if ( $isAdmin || isset($menu['settings']['time-off']))
            <li class="nav-item has-sub @if( str_contains(Request::fullUrl(),$locale.'/timeofftype')|| str_contains(Request::fullUrl(),$locale.'/timeoff'))   @endif">
              <a href="javascript:void(0)" class="d-flex align-items-center ">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">{{__('language.Time Off')}}</span>
              </a>
              <ul class="menu-content">
                <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/time-off-type')) active @endif" aria-haspopup="true">
                  <a href="@if(isset($locale)){{url($locale.'/time-off-type')}} @else {{url('en/time-off-type')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Time Off Type')}}</span>
                  </a>
                </li>
                <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/timeoff') == Request::fullUrl()) active @endif">
                  <a href="@if(isset($locale)) {{route('policy.index', [$locale])}} @else {{route('policy.index', ['en'])}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Time Off')}} {{__('language.Policy')}}</span>
                  </a>
                </li>
              </ul>
            </li>
          @endif
          @if($isAdmin || isset($menu['settings']['hiring']))
            <li class="nav-item has-sub ">
              <a href="javascript:void(0)" class="d-flex align-items-center ">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">{{__('language.Hiring')}}</span>
              </a>
              <ul class="menu-content">
                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/question-types')) active @endif">
                  <a href="@if(isset($locale)){{url($locale.'/question-types')}} @else {{url('en/question-types')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('Question Types')}}</span>
                  </a>
                </li>

                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/questions')) active @endif">
                  <a href="@if(isset($locale)){{url($locale.'/questions')}} @else {{url('en/questions')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Canned Questions')}}</span>
                  </a>
                </li>

                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/email-templates'))   active @endif">
                  <a href="@if(isset($locale)){{url($locale.'/email-templates')}} @else {{url('en/email-templates')}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Email')}} {{__('language.Templates')}}</span>
                  </a>
                </li>
              </ul>
            </li>
          @endif
          @if($isAdmin || isset($menu['settings']['language']) || isset($menu['settings']['secondary language']))
            <li class="nav-item has-sub @if( str_contains(Request::fullUrl(),$locale.'/secondarylanguage')|| str_contains(Request::fullUrl(),$locale.'/language'))
            @endif">
              <a href="javascript:void(0)" class="d-flex align-items-center ">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">
                  {{__('language.Language')}}
                </span>
              </a>
              <ul class="menu-content">
                @if($isAdmin || isset($menu['settings']['language']))
                  <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/language'))  active @endif">
                    <a href="#" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">{{__('language.Change')}} {{__('language.Language')}}</span>
                    </a>
                  </li>
                @endif
                @if($isAdmin || isset($menu['settings']['secondary language']))
                  <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/secondarylanguage')) active @endif " aria-haspopup="true">
                    <a href="@if(isset($locale)){{url($locale.'/secondarylanguage')}} @else {{url('en/secondarylanguage')}} @endif" class="d-flex align-items-center">
                      <span class="menu-title text-truncate">
                        {{__('language.Secondary language')}}
                      </span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif
          @if ($isAdmin || isset($menu['settings']['compensation']))
            <li class="nav-item has-sub @if(str_contains(Request::fullUrl(),$locale.'/compensation-change-reasons')) @endif">
              <a href="javascript:void(0)" class="d-flex align-items-center ">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">{{__('language.Compensation')}}</span>
              </a>
              <ul class="menu-content">
                <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/compensation-change-reasons')) active @endif">
                  <a href="@if(isset($locale)) {{route('compensation-change-reasons.index', [$locale])}} @else {{route('compensation-change-reasons.index', ['en'])}} @endif" class="d-flex align-items-center">
                    <span class="menu-title text-truncate">{{__('language.Change')}} {{__('language.Reasons')}}</span>
                  </a>
                </li>
              </ul>
            </li>
          @endif
          @if ($isAdmin || isset($menu['settings']['approvals']))
            <li class="nav-item  @if( str_contains(Request::fullUrl(),$locale.'/approvals') || str_contains(Request::fullUrl(),$locale.'/approval-workflows')) active @endif">
              <a href="#" class="d-flex align-items-center">
                <i data-feather="circle"></i>
                {{__('language.Approvals')}}</span>
              </a>
            </li>
          @endif
          @if ($isAdmin || isset($menu['settings']['smtp']))
            <li class="nav-item @if(str_contains(Request::fullUrl(),$locale.'/smtp-details')) active @endif">
              <a href="@if(isset($locale)) {{route('smtp-details.index', [$locale])}} @else {{route('smtp-details.index', ['en'])}} @endif" class="d-flex align-items-center">
                <i data-feather="circle"></i>
                <span class="menu-title text-truncate">{{__('language.SMTP Details')}}</span>
              </a>
            </li>
          @endif
          <li class="nav-item @if( str_contains(Request::fullUrl(),$locale.'/importdata')) active @endif">
            <a href="#" class="d-flex align-items-center">
              <i data-feather="circle"></i>
              <span class="menu-title text-truncate">{{__('language.Import')}} {{__('language.Demo')}} {{__('language.Data')}}</span>
            </a>
          </li>
        </ul>
      </li>
<li class="nav-item has-sub @if(request()->is($locale.'/help'))   @endif">
  <a href="javascript:void(0)" class="d-flex align-items-center">
    <i data-feather="book"></i>
    <span class="menu-title text-truncate">
      {{__('language.Support')}}
    </span>
  </a>
  <ul class="menu-content">
    <li class="nav-item @if(request()->is($locale.'/contact-us')) active @endif">
      <a href="@if(isset($locale)){{url($locale.'/contact-us')}} @else {{url('en/contact-us')}} @endif" class="d-flex align-items-center">
        <i data-feather="circle"></i>
        <span class="menu-title text-truncate">{{__('language.Contact Us')}}</span>
      </a>
    </li>
  </ul>
</li>
</ul>
</div>
</div>
<!-- END: Main Menu-->
