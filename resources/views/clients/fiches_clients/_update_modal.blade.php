<div id="updatePopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-in-out">
        <div class="absolute -top-4 -right-4">
            <button onclick="closePopup()" 
                    class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transform hover:rotate-90 transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="mt-3">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center transform hover:scale-105 transition-transform duration-300">
                <span class="bg-blue-500 text-white p-3 rounded-lg shadow-md mr-3">
                    <i class="fas fa-edit text-xl"></i>
                </span>
                <span class="bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">
                    Mise à jour de la fiche
                </span>
            </h3>
            
            <form id="updateForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="fiche_client_id" name="fiche_client_id">

                <div class="grid grid-cols-2 gap-6">
                    <!-- Première colonne -->
                    <div class="space-y-4 transform hover:scale-[1.02] transition-transform duration-300">
                        <div class="form-group">
                            <label for="reception_variables" class="form-label">
                                <i class="fas fa-calendar text-green-500 mr-2"></i>Réception variables
                            </label>
                            <div class="relative">
                                <input type="date" id="reception_variables" name="reception_variables" 
                                    class="form-input pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="preparation_bp" class="form-label">
                                <i class="fas fa-file-alt text-blue-500 mr-2"></i>Préparation BP
                            </label>
                            <div class="relative">
                                <input type="date" id="preparation_bp" name="preparation_bp" 
                                    class="form-input pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="validation_bp_client" class="form-label">
                                <i class="fas fa-check-circle text-purple-500 mr-2"></i>Validation BP client
                            </label>
                            <div class="relative">
                                <input type="date" id="validation_bp_client" name="validation_bp_client" 
                                    class="form-input pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deuxième colonne -->
                    <div class="space-y-4 transform hover:scale-[1.02] transition-transform duration-300">
                        <div class="form-group">
                            <label for="preparation_envoie_dsn" class="form-label">
                                <i class="fas fa-paper-plane text-indigo-500 mr-2"></i>Préparation DSN
                            </label>
                            <div class="relative">
                                <input type="date" id="preparation_envoie_dsn" name="preparation_envoie_dsn" 
                                    class="form-input pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="accuses_dsn" class="form-label">
                                <i class="fas fa-receipt text-yellow-500 mr-2"></i>Accusés DSN
                            </label>
                            <div class="relative">
                                <input type="date" id="accuses_dsn" name="accuses_dsn" 
                                    class="form-input pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes avec animation spéciale -->
                <div class="form-group mt-6 transform hover:scale-[1.01] transition-transform duration-300">
                    <label for="notes" class="form-label flex items-center group">
                        <span class="bg-red-500 text-white p-2 rounded-lg shadow-md mr-2 group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-sticky-note"></i>
                        </span>
                        <span class="text-red-500 font-semibold">Notes</span>
                    </label>
                    <div class="relative">
                        <textarea id="notes" name="notes" rows="3" 
                            class="form-textarea pl-10 resize-none focus:ring-red-500"></textarea>
                        <div class="absolute top-3 left-0 pl-3 pointer-events-none">
                            <i class="fas fa-pen text-red-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closePopup()" 
                        class="btn-action btn-cancel group hover:scale-105 transform transition-all duration-300">
                        <span class="bg-gray-200 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-times text-gray-600"></i>
                        </span>
                        <span>Annuler</span>
                    </button>
                    <button type="submit" 
                        class="btn-action btn-submit group hover:scale-105 transform transition-all duration-300">
                        <span class="bg-white bg-opacity-20 p-2 rounded-lg group-hover:rotate-12 transition-transform duration-300">
                            <i class="fas fa-save text-white"></i>
                        </span>
                        <span>Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-label {
        @apply block text-sm font-medium text-gray-700 mb-1;
    }

    .form-input, .form-textarea {
        @apply shadow-sm border-gray-300 rounded-lg w-full py-2.5 px-3 text-gray-700 leading-tight 
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent 
               transition-all duration-200 hover:shadow-md;
    }

    .btn-action {
        @apply px-6 py-3 rounded-lg text-sm font-medium transition-all duration-300 
               flex items-center justify-center gap-3 shadow-md hover:shadow-xl;
    }

    .btn-cancel {
        @apply bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 
               hover:from-gray-200 hover:to-gray-300 border border-gray-300;
    }

    .btn-submit {
        @apply bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 text-white 
               hover:from-blue-600 hover:via-blue-700 hover:to-blue-800;
    }

    @keyframes modalSlideIn {
        0% {
            transform: translateY(-30px) scale(0.9);
            opacity: 0;
        }
        100% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes modalSlideOut {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(0.9);
            opacity: 0;
        }
    }

    #updatePopup:not(.hidden) > div {
        animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-exit {
        animation: modalSlideOut 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-submit:after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to right,
            rgba(255,255,255,0) 0%,
            rgba(255,255,255,0.3) 50%,
            rgba(255,255,255,0) 100%
        );
        transform: rotate(45deg);
        transition: all 0.5s;
        opacity: 0;
    }

    .btn-submit:hover:after {
        opacity: 1;
        left: 100%;
    }
</style>

<script>
    function closePopup() {
        const popup = document.getElementById('updatePopup');
        const modalContent = popup.querySelector('div');
        modalContent.classList.add('modal-exit');
        setTimeout(() => {
            popup.classList.add('hidden');
            modalContent.classList.remove('modal-exit');
        }, 280);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('updatePopup');
        const modalContent = popup.querySelector('div');

        popup.addEventListener('click', function(e) {
            if (e.target === this) {
                closePopup();
            }
        });

        modalContent.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        popup.addEventListener('shown.bs.modal', function() {
            document.querySelector('#reception_variables').focus();
        });
    });
</script> 