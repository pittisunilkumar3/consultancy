<div class="">
    <div class="">
        <!-- Conditionally show content based on resource type -->
        @if($resource->intro_video_type == RESOURCE_TYPE_LOCAL)
            <video id="video-player" controls>
                <source src="{{ getFileUrl($resource->intro_video) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @elseif($resource->intro_video_type == RESOURCE_TYPE_YOUTUBE_ID)
            <div class="video-player-area">
                <div class="youtube-player">
                    <div id="youtube-player-video" class="youtube-video" data-video-id="{{ $resource->intro_video }}"></div>

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
        @endif
    </div>
</div>
