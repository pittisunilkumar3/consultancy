<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ $resource->title }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<div class="row rg-20">
    <div class="col-12">
        <!-- Conditionally show content based on resource type -->
        @if($resource->resource_type == RESOURCE_TYPE_LOCAL)
            <video id="video-player" controls>
                <source src="{{ getFileUrl($resource->resource) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @elseif($resource->resource_type == RESOURCE_TYPE_PDF)
            <iframe src="{{ getFileUrl($resource->resource) }}" class="w-100" height="600" style="border: none;"></iframe>
        @elseif($resource->resource_type == RESOURCE_TYPE_IMAGE)
            <!-- Image -->
            <img src="{{ getFileUrl($resource->resource) }}" alt="{{ $resource->title }}" class="w-100">
        @elseif($resource->resource_type == RESOURCE_TYPE_AUDIO)
            <audio id="audio-player" controls>
                <source src="{{ getFileUrl($resource->resource) }}" type="audio/mp3">
                Your browser does not support the audio tag.
            </audio>
        @elseif($resource->resource_type == RESOURCE_TYPE_YOUTUBE_ID)
            <div class="video-player-area">
                <div class="youtube-player">
                    <div id="youtube-player-video" class="youtube-video" data-video-id="{{ $resource->resource }}"></div>

                    <button class="youtube-video-overBtn"><i class="fa fa-play"></i></button>

                    <div class="youtube-player-controls">
                        <button class="play-button">
                            <i class="fa fa-play"></i>
                        </button>
                        <progress class="progress-bar" min="0" max="100" value="0"></progress>
                        <span class="progress-text"></span>

                        <button class="sound-button">
                            <i class="fa fa-volume-up"></i>
                        </button>
                        <progress class="sound-bar" min="0" max="100" value="0"></progress>
                        <button class="fullscreen-button">
                            <i class="fa fa-expand"></i>
                        </button>
                    </div>
                </div>
            </div>
        @elseif($resource->resource_type == RESOURCE_TYPE_SLIDE)
            {!! $resource->resource !!}
        @endif
    </div>

</div>
