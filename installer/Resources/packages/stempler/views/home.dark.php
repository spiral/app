<extends:layout.base title="[[The PHP Framework for future Innovators]]"/>

<stack:push name="styles">
    <link rel="stylesheet" href="/styles/welcome.css"/>
</stack:push>

<define:body>
    <h1 class="main-title">The PHP Framework <span>for future Innovators</span></h1>

    <p class="main-description">Spiral Framework is a high-performance, intuitive platform for building scalable
        enterprise applications, combining powerful features with an efficient developer experience.</p>

    <div class="box">
        <div class="item">
            <h2 class="item__title">
                <a href="https://spiral.dev/docs" target="_blank">Documentation</a>
            </h2>
            <p class="item__text">Spiral provides comprehensive documentation that covers every aspect of the
                framework,
                catering to both newcomers and experienced users. We highly recommend exploring the documentation to
                fully
                understand and utilize Spiral's capabilities.</p>
        </div>
        <div class="item">
            <h2 class="item__title">
                <a href="https://cycle-orm.dev/" target="_blank">Cycle ORM</a>
            </h2>
            <p class="item__text">Cycle ORM is a flexible and powerful object-relational mapping solution for PHP,
                making it
                simple to work with databases within Spiral applications. With its focus on performance and ease of
                use, Cycle
                ORM streamlines data persistence and retrieval.</p>
        </div>
        <div class="item">
            <h2 class="item__title">
                <a href="https://roadrunner.dev/" target="_blank">RoadRunner</a>
            </h2>
            <p class="item__text">RoadRunner is a high-performance application server specifically designed for PHP,
                offering
                a significant performance boost to Spiral applications. By warming up code only once and
                communicating per
                request, RoadRunner ensures efficient application handling.</p>
        </div>
        <div class="item">
            <h2 class="item__title">
                <a href="https://github.com/spiral-packages/" target="_blank">Ecosystem Packages</a>
            </h2>
            <p class="item__text">Spiral's vibrant ecosystem includes a variety of packages and tools that enhance
                the
                framework's functionality, making it easier to develop and maintain your applications. These
                resources,
                supported by an active community, ensure that your Spiral projects reach their full potential.</p>
        </div>
    </div>
    <div class="version"><span>Spiral Framework v3.7</span> <span>PHP @php echo PHP_VERSION; @endphp</span></div>

    <div class="logo">
        <a href="https://spiral.dev/">
            <img src="/images/logo.svg" alt="Framework Logotype" width="200px"/>
        </a>
    </div>
</define:body>
