<extends:layout.base title="[[The PHP Framework for future Innovators]]"/>

<stack:push name="styles">
    <link rel="stylesheet" href="/styles/welcome.css"/>
</stack:push>

<define:body>
    <h1 class="main-title">The PHP Framework <span>for future Innovators</span></h1>

    <p class="main-description">Spiral Framework is a high-performance, intuitive platform for building scalable
        enterprise applications, combining powerful features with an efficient developer experience.</p>

    <div class="box">
        <div class="items">
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

        <nav class="links">
            <a class="links-item" href="https://github.com/spiral" tabindex="0" target="_blank" aria-label="GitHub">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" fill="none">
                    <path fill="currentColor" fill-rule="evenodd"
                          d="M10.2 0C4.5 0 0 4.6 0 10.3c0 4.5 3 8.3 7 9.7.5 0 .7-.2.7-.5v-2c-2.9.7-3.5-1.1-3.5-1.1-.4-1.2-1.1-1.5-1.1-1.5-1-.7 0-.7 0-.7 1 .1 1.6 1.1 1.6 1.1 1 1.6 2.4 1.1 3 .8 0-.6.3-1 .6-1.3-2.2-.3-4.6-1.2-4.6-5 0-1.2.4-2.1 1-2.8 0-.3-.4-1.3.1-2.8 0 0 .9-.2 2.8 1.1a9.8 9.8 0 0 1 5.1 0c2-1.3 2.8-1 2.8-1 .6 1.4.2 2.4.1 2.7a4 4 0 0 1 1 2.7c0 4-2.3 4.8-4.6 5 .4.4.7 1 .7 2v2.8c0 .3.2.6.7.5 4-1.4 7-5.2 7-9.7C20.3 4.6 15.7 0 10.1 0Z"
                          clip-rule="evenodd"></path>
                </svg>
            </a>
            <a class="links-item" href="https://discord.gg/V6EK4he" tabindex="0" target="_blank"
               aria-label="Discord">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="16" fill="none">
                    <path fill="currentColor"
                          d="M17.6 1.3C16.2.7 14.8.3 13.3 0l-.6 1.1a16 16 0 0 0-4.7 0L7.4 0C6 .3 4.5.7 3.2 1.3.5 5.3-.3 9.3 0 13.1c1.6 1.1 3.3 2 5.2 2.6L6.5 14c-.7-.2-1.2-.5-1.8-.8l.4-.4a12.3 12.3 0 0 0 10.5 0l.4.4-1.7.8 1 1.8c2-.6 3.8-1.5 5.3-2.6.5-4.5-.7-8.4-3-11.8ZM6.9 10.7c-1 0-1.8-1-1.8-2 0-1.2.8-2.1 1.8-2.1s1.9.9 1.9 2c0 1.2-.8 2.1-1.9 2.1Zm7 0c-1.1 0-2-1-2-2 0-1.2.9-2.1 2-2.1 1 0 1.8.9 1.8 2 0 1.2-.9 2.1-1.9 2.1Z"></path>
                </svg>
            </a>
            <a class="links-item" href="https://forum.roadrunner.dev/" tabindex="0" target="_blank"
               aria-label="Discourse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                    <path fill="currentColor"
                          d="M12 2.4c-5.2 0-9.59 4.22-9.59 9.43v9.77h9.6c5.2 0 9.43-4.4 9.43-9.6 0-5.21-4.23-9.6-9.43-9.6Z"></path>
                    <path class="discourse-path-1" fill="#1F242B"
                          d="M12.1 6.05a5.85 5.85 0 0 0-5.13 8.63l-1.06 3.4 3.8-.85a5.85 5.85 0 1 0 2.4-11.18Z"></path>
                    <path class="discourse-path-2" fill="#161B22"
                          d="M16.68 15.52a5.84 5.84 0 0 1-6.97 1.7l-3.8.87 3.87-.46a5.84 5.84 0 0 0 6.96-9.29 5.84 5.84 0 0 1-.06 7.17Z"
                          opacity=".2"></path>
                    <path class="discourse-path-3" fill="#A2ACBA"
                          d="M16.35 14.4a5.84 5.84 0 0 1-6.75 2.43l-3.7 1.26 3.8-.86a5.84 5.84 0 0 0 5.97-9.97 5.84 5.84 0 0 1 .68 7.15Z"
                          opacity=".4"></path>
                    <path class="discourse-path-3" fill="#A2ACBA"
                          d="M7.32 14.81a5.85 5.85 0 0 1 9.43-6.47 5.84 5.84 0 0 0-9.78 6.34l-1.06 3.4 1.4-3.27Z"
                          opacity=".4"></path>
                    <path class="discourse-path-2" fill="#161B22"
                          d="M6.96 14.68a5.85 5.85 0 0 1 8.7-7.42A5.85 5.85 0 0 0 6.6 14.6l-.7 3.5 1.06-3.4Z"
                          opacity=".2"></path>
                </svg>
            </a>
        </nav>
    </div>
    <div class="version"><span>Spiral Framework v3.7</span> <span>PHP @php echo PHP_VERSION; @endphp</span></div>

    <div class="logo">
        <a href="https://spiral.dev/">
            <img src="/images/logo.svg" alt="Framework Logotype" width="200px"/>
        </a>
    </div>
</define:body>
