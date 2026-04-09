@extends('layout')
@section('content')
<div class="max-w-4xl mx-auto py-8 font-sans" x-data="videoTracking({{ $content->id }})">
    <div class="mb-4">
        <a href="{{ url('subjects/' . $content->subject_id) }}" class="text-blue-600 hover:underline">&larr; Retour à la matière</a>
    </div>

    <h1 class="text-3xl font-bold mb-2">{{ $content->title }}</h1>
    <p class="text-gray-600 mb-6">{{ $content->description }}</p>
    
    <div class="bg-black rounded-lg overflow-hidden shadow-lg mb-6">
        <video 
            id="learning-video"
            controls 
            class="w-full"
            src="{{ $content->video_url }}"
            @play="handlePlay"
            @pause="handlePause"
            @timeupdate="handleTimeUpdate"
            @ratechange="handleRateChange"
            @seeked="handleSeeked"
        ></video>
    </div>

    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
        <div>
            <h3 class="font-bold text-gray-700">Progression</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
              <div class="bg-blue-600 h-2.5 rounded-full" :style="'width:' + progressPercent + '%'"></div>
            </div>
            <p class="text-sm mt-1 text-gray-600"><span x-text="Math.round(progressPercent)"></span>% complété (Vu : <span x-text="watchedSeconds"></span>s / {{ $content->duration_seconds }}s)</p>
        </div>
        <div>
           <h3 class="font-bold text-gray-700">Status</h3>
           <p class="mt-1" x-show="!isCompleted" class="text-orange-600">En cours d'apprentissage</p>
           <p class="mt-1 text-green-600 font-bold" x-show="isCompleted">✓ Complété</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('videoTracking', (contentId) => ({
        contentId: contentId,
        videoEl: null,
        token: null,
        segmentStart: 0,
        lastReportTime: 0,
        playbackRate: 1.0,
        trackerInterval: null,
        progressPercent: {{ $progress ? ($progress->watched_seconds / max($content->duration_seconds, 1)) * 100 : 0 }},
        watchedSeconds: {{ $progress ? $progress->watched_seconds : 0 }},
        isCompleted: {{ $progress && $progress->completed ? 'true' : 'false' }},
        duration: {{ $content->duration_seconds }},

        init() {
            this.videoEl = document.getElementById('learning-video');
        },
        
        async requestToken(position) {
            try {
                const response = await axios.post(`/api/contents/${this.contentId}/segment-token`, {
                    position: position
                }, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                this.token = response.data.token;
                this.segmentStart = response.data.segment_start;
                this.lastReportTime = position;
            } catch(e) {
                console.error("Erreur émission token", e);
            }
        },

        async reportSegment(endPos) {
            if(!this.token || this.isCompleted) return;
            try {
                const response = await axios.post(`/api/contents/${this.contentId}/progress`, {
                    token: this.token,
                    segment_start: this.segmentStart,
                    segment_end: endPos,
                    playback_rate: this.playbackRate
                });
                
                this.watchedSeconds = response.data.watched_seconds;
                this.isCompleted = response.data.completed;
                this.progressPercent = Math.min((this.watchedSeconds / this.duration) * 100, 100);
            } catch(e) {
                console.error("Erreur validation segment", e);
            }
        },

        async handlePlay() {
            if(this.isCompleted) return;
            this.playbackRate = this.videoEl.playbackRate;
            await this.requestToken(Math.floor(this.videoEl.currentTime));
            
            // Envoyer toutes les 30 sec (défini par backend)
            this.trackerInterval = setInterval(async () => {
                let current = Math.floor(this.videoEl.currentTime);
                await this.reportSegment(current);
                await this.requestToken(current);
            }, 30000);
        },

        async handlePause() {
            if(this.trackerInterval) clearInterval(this.trackerInterval);
            if(this.token && !this.isCompleted) {
                await this.reportSegment(Math.floor(this.videoEl.currentTime));
            }
            this.token = null;
        },

        async handleSeeked() {
            // Lors d'un seek, on valide le segment précédent, pus on reprend un nouveau token
            if(this.token && !this.isCompleted) {
                await this.reportSegment(this.lastReportTime); // validation avortée
            }
            if(!this.videoEl.paused) {
                await this.requestToken(Math.floor(this.videoEl.currentTime));
            }
        },

        handleRateChange() {
            this.playbackRate = this.videoEl.playbackRate;
        },
        
        handleTimeUpdate() {
            // Update UI locale si besoin
        }
    }));
});
</script>
@endsection
