<extends:layout.base title="[[Access Forbidden]]"/>

<define:body>
    <div class="error-code-text">403</div>

    <h1 class="main-title">
        Ooops! <span>[[Access Forbidden]]</span>
    </h1>

    <div class="version">
        <span>@spiralVersion</span>
        <span>@phpVersion</span>
        @if($debug)
        <span>[[This view file is located in]] <b>app/views/exception/404.dark.php</b>.</span>
        @endif
    </div>
</define:body>
