<div class="pre-loader">
    <div class="pre-loader-box">
        <div class="loader-logo">
            <img src="{{ asset('xv/public/backoffice/vendors/images/deskapp-logo.svg') }}" alt="" />
        </div>

        <!-- Cube Animation -->
        <div class="loader-container">
            <div class="cube" id="cube1"></div>
            <div class="cube" id="cube2"></div>
            <div class="cube" id="cube3"></div>
            <div class="cube" id="cube4"></div>
            <div class="cube" id="cube5"></div>
            {{-- <div class="cube" id="cube6"></div>
            <div class="cube" id="cube7"></div>
            <div class="cube" id="cube8"></div>
            <div class="cube" id="cube9"></div> --}}
        </div>

        <!-- Progress Bar and Percentage -->
        <div class="loader-progress" id="progress_div">
            <div class="bar" id="bar1"></div>
        </div>
        <div class="percent" id="percent1">0%</div>
        <div class="loading-text">Loading...</div>

        <!-- Additional Animation for Directors -->
        {{-- <div class="flex items-center justify-center w-16 h-16 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"> --}}
            <div class="px-3 py-1 text-xs font-medium leading-none text-center text-blue-800 bg-blue-200 rounded-full animate-pulse dark:bg-blue-900 dark:text-blue-200">Exteralliance Connexion...</div>
        {{-- </div> --}}
    </div>
</div>

<style>
    .pre-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #f2f2f2;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.pre-loader-box {
    text-align: center;
}

.loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.cube {
    width: 50px;
    height: 50px;
    background-color: #bd15ff;
    animation: cubeAppear 1s ease-in-out infinite;
}

@keyframes cubeAppear {
    0%, 100% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1);
        opacity: 1;
    }
}

.cube:nth-child(1) { animation-delay: 0s; }
.cube:nth-child(2) { animation-delay: 0.2s; }
.cube:nth-child(3) { animation-delay: 0.4s; }
.cube:nth-child(4) { animation-delay: 0.6s; }
.cube:nth-child(5) { animation-delay: 0.8s; }
/* .cube:nth-child(6) { animation-delay: 0.10s; }
.cube:nth-child(7) { animation-delay: 0.12s; }
.cube:nth-child(8) { animation-delay: 0.14s; }
.cube:nth-child(9) { animation-delay: 0.6s; } */

.loader-progress {
    position: relative;
    width: 300px;
    height: 10px;
    background-color: #e0e0e0;
    margin: 0 auto;
}

.bar {
    height: 100%;
    background-color: #714b86;
    width: 0;
}

.percent {
    font-size: 24px;
    margin: 10px 0;
}

.loading-text {
    margin-bottom: 20px;
    font-size: 18px;
    color: #555;
}

.flex {
    margin-top: 20px;
}

</style>
