@extends('layouts.app')

@php
$seo = [
    'title'       => $page->getSeoTitle(),
    'description' => $page->getSeoDescription(),
    'canonical'   => route('pages.contato'),
];
@endphp

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <header class="mb-12">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-1.5 h-10 bg-accent rounded-full"></div>
            <h1 class="text-3xl md:text-4xl font-bold text-text-high"
                style="font-family: var(--font-heading)">
                {{ $page->title }}
            </h1>
        </div>
    </header>

    {{-- Conteúdo da página --}}
    <div class="prose prose-lg max-w-none
                prose-headings:font-bold prose-headings:text-text-high
                prose-p:text-text-medium prose-p:leading-relaxed
                prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                prose-strong:text-text-high mb-12">
        {!! $page->content !!}
    </div>

    {{-- Formulário de contato --}}
    <div class="bg-bg-surface border border-border-subtle rounded-xl p-8"
         x-data="contactForm()">

        <h2 class="text-2xl font-bold text-text-high mb-6"
            style="font-family: var(--font-heading)">
            Envie uma mensagem
        </h2>

        <div x-show="submitted" class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-accent/10 rounded-full mb-4">
                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-text-high font-medium text-lg">Mensagem enviada!</p>
            <p class="text-text-medium mt-1">Entraremos em contato em breve.</p>
        </div>

        <form x-show="!submitted"
              @submit.prevent="submit()"
              class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-text-high mb-2">Nome</label>
                    <input type="text" x-model="form.name" required
                           placeholder="Seu nome"
                           class="w-full px-4 py-3 bg-bg-elevated border border-border-subtle rounded-lg
                                  text-text-high placeholder:text-text-low
                                  focus:outline-none focus:border-border-focus transition-colors duration-150">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-high mb-2">Email</label>
                    <input type="email" x-model="form.email" required
                           placeholder="seu@email.com"
                           class="w-full px-4 py-3 bg-bg-elevated border border-border-subtle rounded-lg
                                  text-text-high placeholder:text-text-low
                                  focus:outline-none focus:border-border-focus transition-colors duration-150">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-text-high mb-2">Assunto</label>
                <input type="text" x-model="form.subject" required
                       placeholder="Como podemos ajudar?"
                       class="w-full px-4 py-3 bg-bg-elevated border border-border-subtle rounded-lg
                              text-text-high placeholder:text-text-low
                              focus:outline-none focus:border-border-focus transition-colors duration-150">
            </div>

            <div>
                <label class="block text-sm font-medium text-text-high mb-2">Mensagem</label>
                <textarea x-model="form.message" required rows="5"
                          placeholder="Escreva sua mensagem..."
                          class="w-full px-4 py-3 bg-bg-elevated border border-border-subtle rounded-lg
                                 text-text-high placeholder:text-text-low
                                 focus:outline-none focus:border-border-focus transition-colors duration-150
                                 resize-none"></textarea>
            </div>

            <button type="submit"
                    :disabled="loading"
                    class="w-full md:w-auto px-8 py-3 bg-accent text-white rounded-lg font-medium
                           hover:opacity-90 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed
                           flex items-center gap-2">
                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <span x-text="loading ? 'Enviando...' : 'Enviar mensagem'"></span>
            </button>

        </form>
    </div>

</div>

<script>
function contactForm() {
    return {
        submitted: false,
        loading: false,
        form: { name: '', email: '', subject: '', message: '' },
        submit() {
            this.loading = true;
            setTimeout(() => {
                this.loading = false;
                this.submitted = true;
            }, 1500);
        }
    }
}
</script>
@endsection