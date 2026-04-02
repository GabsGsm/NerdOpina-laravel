@props(['compact' => false])

@if ($compact)
<div class="bg-bg-surface rounded-xl p-5 border border-border-subtle"
     x-data="{ email: '', submitted: false }">
    <h3 class="font-heading font-bold text-text-high mb-1 text-sm uppercase tracking-wide">
        Newsletter
    </h3>
    <p class="text-xs text-text-medium mb-4">
        Receba as melhores notícias nerd direto no seu email.
    </p>

    <div x-show="submitted" x-cloak
         class="flex items-center gap-2 text-sm text-accent font-medium py-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Inscrito com sucesso!
    </div>

    <form x-show="!submitted"
          @submit.prevent="submitted = true; setTimeout(() => { submitted = false; email = '' }, 4000)"
          class="space-y-2">
        <input type="email" x-model="email" placeholder="seu@email.com" required
               class="w-full px-3 py-2 bg-bg-elevated border border-border-subtle rounded-lg
                      text-sm text-text-high placeholder:text-text-low
                      focus:outline-none focus:border-border-focus transition-colors duration-150">
        <button type="submit"
                class="w-full px-4 py-2 bg-accent text-white rounded-lg text-sm font-medium
                       hover:opacity-90 transition-all duration-150">
            Inscrever-se
        </button>
    </form>
</div>

@else
<div class="relative overflow-hidden bg-bg-surface rounded-xl p-8 border border-border-subtle text-center max-w-2xl mx-auto"
     x-data="{ email: '', submitted: false }">

    {{-- Glow decorativo --}}
    <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-32 h-32 bg-accent rounded-full blur-3xl opacity-10 pointer-events-none"></div>

    <div class="relative">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-accent/10 rounded-xl mb-4">
            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h2 class="font-heading text-2xl font-bold text-text-high mb-2">
            Assine nossa newsletter
        </h2>
        <p class="text-text-medium mb-6 max-w-sm mx-auto text-sm">
            Receba reviews, notícias e análises sobre o universo nerd direto no seu email.
        </p>

        <div x-show="submitted" x-cloak
             class="inline-flex items-center gap-2 text-accent font-medium py-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Obrigado por se inscrever!
        </div>

        <form x-show="!submitted"
              @submit.prevent="submitted = true; setTimeout(() => { submitted = false; email = '' }, 4000)"
              class="flex gap-2 max-w-md mx-auto">
            <input type="email" x-model="email" placeholder="Digite seu email" required
                   class="flex-1 px-4 py-3 bg-bg-elevated border border-border-subtle rounded-lg
                          text-text-high placeholder:text-text-low
                          focus:outline-none focus:border-border-focus transition-colors duration-150 text-sm">
            <button type="submit"
                    class="px-6 py-3 bg-accent text-white rounded-lg font-medium text-sm
                           hover:opacity-90 transition-all duration-150 shrink-0">
                Inscrever-se
            </button>
        </form>
    </div>
</div>
@endif
