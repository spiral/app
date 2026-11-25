<extends:layout.base title="[[Page not found]]"/>

<define:body>
    <div class="error-code-text">404</div>

    <h1 class="main-title">
        Ooops! <span>[[Page not found]]</span>
    </h1>

    <p class="main-description">[[Sorry, but the page you are looking for is not found. Please, make sure you have typed the correct URL.]]</p>
    <div class="version">
        <span>@spiralVersion</span>
        <span>@phpVersion</span>
        @if($debug)
        <span>[[This view file is located in]] <b>app/views/exception/404.dark.php</b>.</span>
        @endif
    </div>
</define:body>
