
<div id="miniPlayer" class="card">
    <div class="card-body">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg overflow-hidden ring-1 ring-black/10 dark:ring-white/10">
                
                <img src="<?php echo e(asset('images/logo/logo.png')); ?>" alt="ECCA" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <div class="text-sm font-semibold">Radio ECCA</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 truncate">En vivo</div>
            </div>
        </div>

        <div class="mt-3">
            <audio id="audioStream" src="<?php echo e(config('app.stream_url')); ?>" controls preload="none" class="w-full"></audio>
        </div>
        <div class="mt-2 flex gap-2">
            <span class="chip-brand"><i class="fa-solid fa-music"></i> Al aire</span>
            <span class="chip-brand"><i class="fa-solid fa-broadcast-tower"></i> Streaming</span>
        </div>
    </div>
</div><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/player-mini.blade.php ENDPATH**/ ?>