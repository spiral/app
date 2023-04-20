<extends:layout.base title="[[Something went wrong]]"/>

<define:body>
    <div class="error-code-text">500</div>

    <h1 class="main-title">
        Ooops! <span>[[Something went wrong]]</span>
    </h1>

    <p class="main-description">{{ $exception->getMessage() }}</p>
    <div class="version">
        <span>Spiral Framework @spiralVersion</span>
        <span>@phpVersion</span>
        @if($debug)
        <span>[[This view file is located in]] <b>app/views/exception/404.dark.php</b>.</span>
        @endif
    </div>
</define:body>
