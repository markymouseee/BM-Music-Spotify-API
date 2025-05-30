@extends('app')

@section('layout')
   <nav class="navbar navbar-expand-lg position-sticky top-0 shadow-sm"
        style="background-color: rgba(34, 40, 49, 0.85); backdrop-filter: blur(5px); z-index: 1030;">
        <div class="container">
            <a class="navbar-brand text-light fw-bold fs-4" href="#">
                <svg width="40px" height="40px" viewBox="0 0 1024 1024" class="icon" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M209.11128 258.0742c20.523667-7.860128 148.469083 191.699787 299.121535 191.699787s267.681023-206.110021 283.837953-195.193177-65.501066 208.730064-54.147548 279.034542c20.523667 145.849041 118.338593 102.181663 127.072069 288.204691 3.49339 80.347974-74.671215 205.236674-364.185928 201.743283S158.893796 906.098081 158.893796 822.256716c0-151.089126 80.784648-131.002132 105.675053-289.951386 10.480171-66.374414-72.92452-262.440938-55.457569-274.23113z"
                        fill="#fffff" />
                    <path
                        d="M660.195289 596.496375H566.310427V134.058849a43.667377 43.667377 0 0 0 40.610661-43.667378V43.667377a43.667377 43.667377 0 0 0-43.667377-43.667377H458.888679a43.667377 43.667377 0 0 0-43.667378 43.667377v48.470789a43.667377 43.667377 0 0 0 40.610661 43.667378v460.690831h-87.334754a20.960341 20.960341 0 0 0-20.960341 20.960341v229.690406a20.960341 20.960341 0 0 0 20.960341 20.960341h292.134754a20.960341 20.960341 0 0 0 22.270363-20.960341v-229.690406a20.960341 20.960341 0 0 0-22.707036-20.960341z"
                        fill="#D46882" />
                    <path d="M423.518103 653.70064l189.953092 0 0 67.684435-189.953092 0 0-67.684435Z" fill="#E6E9ED" />
                    <path d="M423.518103 755.882303l189.953092 0 0 67.684435-189.953092 0 0-67.684435Z" fill="#E6E9ED" />
                </svg>
                BM MUSIC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-medium sign-up-link d-flex text-decoration-none align-items-center gap-1"
                            href="#" data-bs-toggle="modal" data-bs-target="#sign-up-modal">
                            <svg width="20" height="20" viewBox="0 0 32 32" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                    sketch:type="MSPage">
                                    <g id="Icon-Set" sketch:type="MSLayerGroup"
                                        transform="translate(-360.000000, -1087.000000)" fill="currentColor">
                                        <path
                                            d="M376,1117 C368.268,1117 362,1110.73 362,1103 C362,1095.27 368.268,1089 376,1089 C383.732,1089 390,1095.27 390,1103 C390,1110.73 383.732,1117 376,1117 L376,1117 Z M376,1087 C367.163,1087 360,1094.16 360,1103 C360,1111.84 367.163,1119 376,1119 C384.837,1119 392,1111.84 392,1103 C392,1094.16 384.837,1087 376,1087 L376,1087 Z M376.879,1096.46 C376.639,1096.22 376.311,1096.15 376,1096.21 C375.689,1096.15 375.361,1096.22 375.121,1096.46 L369.465,1102.12 C369.074,1102.51 369.074,1103.14 369.465,1103.54 C369.854,1103.93 370.488,1103.93 370.879,1103.54 L375,1099.41 L375,1110 C375,1110.55 375.447,1111 376,1111 C376.553,1111 377,1110.55 377,1110 L377,1099.41 L381.121,1103.54 C381.512,1103.93 382.145,1103.93 382.535,1103.54 C382.926,1103.14 382.926,1102.51 382.535,1102.12 L376.879,1096.46 L376.879,1096.46 Z"
                                            id="arrow-up-circle" sketch:type="MSShapeGroup">

                                        </path>
                                    </g>
                                </g>
                            </svg>
                            
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection