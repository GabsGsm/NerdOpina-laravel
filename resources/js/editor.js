import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import Placeholder from '@tiptap/extension-placeholder'

// Extensão de imagem customizada com suporte ao atributo style (largura + alinhamento)
const CustomImage = Image.extend({
    addAttributes() {
        return {
            ...this.parent?.(),
            style: {
                default: null,
                parseHTML: el => el.getAttribute('style') || null,
                renderHTML: attrs => attrs.style ? { style: attrs.style } : {},
            },
        }
    },
})

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-editor]').forEach(el => {
        const wrapper    = el.closest('.editor-wrapper')
        const uploadUrl  = el.dataset.uploadUrl
        const textarea   = wrapper.querySelector('textarea[data-editor-output]')
        const fileInput  = wrapper.querySelector('input[data-image-upload]')

        const editor = new Editor({
            element: el,
            extensions: [
                StarterKit,
                CustomImage.configure({ inline: false, allowBase64: false }),
                Link.configure({ openOnClick: false }),
                Placeholder.configure({
                    placeholder: el.dataset.placeholder ?? 'Escreva o conteúdo aqui...',
                }),
            ],
            content: el.dataset.content || '',
            onUpdate({ editor }) {
                textarea.value = editor.getHTML()
            },
        })

        // Sync inicial para garantir valor no textarea ao abrir
        textarea.value = editor.getHTML()

        // Toolbar de imagem flutuante
        const imageToolbar = createImageToolbar(wrapper, editor)
        let dismissImageToolbar = false
        let lastImagePos        = null

        // Segundo clique na mesma imagem fecha o toolbar
        el.addEventListener('mousedown', e => {
            if (e.target.tagName !== 'IMG') return
            if (!imageToolbar.classList.contains('hidden')) {
                dismissImageToolbar = true
            }
        })

        // Highlight botão ativo conforme seleção
        editor.on('selectionUpdate', () => {
            updateActiveButtons(editor, wrapper)

            const { selection } = editor.state
            const isImageSelected = selection?.node?.type?.name === 'image'

            if (!isImageSelected) {
                dismissImageToolbar = false
                lastImagePos        = null
                imageToolbar.classList.add('hidden')
                return
            }

            // Clicou em imagem diferente — reseta dismiss
            if (selection.from !== lastImagePos) {
                dismissImageToolbar = false
                lastImagePos        = selection.from
            }

            if (dismissImageToolbar) return

            syncImageToolbarState(imageToolbar, editor.getAttributes('image'))
            positionImageToolbar(editor, wrapper, imageToolbar)
            imageToolbar.classList.remove('hidden')
        })
        editor.on('update', () => {
            updateActiveButtons(editor, wrapper)
            textarea.value = editor.getHTML()
        })
        editor.on('blur', ({ event }) => {
            const relatedTarget = event?.relatedTarget
            if (relatedTarget && imageToolbar.contains(relatedTarget)) return
            imageToolbar.classList.add('hidden')
        })

        // Toolbar principal
        wrapper.querySelectorAll('[data-cmd]').forEach(btn => {
            btn.addEventListener('mousedown', e => {
                e.preventDefault()
                handleCommand(btn.dataset.cmd, editor, fileInput)
            })
        })

        // Upload de imagem
        if (fileInput) {
            fileInput.addEventListener('change', async () => {
                const file = fileInput.files[0]
                if (!file) return

                const formData = new FormData()
                formData.append('image', file)
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content)

                try {
                    const res  = await fetch(uploadUrl, { method: 'POST', body: formData })
                    const json = await res.json()
                    if (json.url) {
                        editor.chain().focus().setImage({ src: json.url }).run()
                    }
                } catch (e) {
                    console.error('Erro ao enviar imagem:', e)
                } finally {
                    fileInput.value = ''
                }
            })
        }
    })
})

// ─── Toolbar de imagem flutuante ─────────────────────────────────────────────

function createImageToolbar(wrapper, editor) {
    const el = document.createElement('div')
    el.setAttribute('data-image-toolbar', '')
    el.className = 'img-toolbar hidden'
    el.innerHTML = `
        <span class="img-toolbar__label">Tamanho:</span>
        <button type="button" class="editor-btn" data-img-cmd="width" data-img-val="25%">25%</button>
        <button type="button" class="editor-btn" data-img-cmd="width" data-img-val="50%">50%</button>
        <button type="button" class="editor-btn" data-img-cmd="width" data-img-val="75%">75%</button>
        <button type="button" class="editor-btn" data-img-cmd="width" data-img-val="100%">100%</button>
        <span class="img-toolbar__sep"></span>
        <span class="img-toolbar__label">Alinhar:</span>
        <button type="button" class="editor-btn" data-img-cmd="align" data-img-val="left" title="Alinhar à esquerda">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14"/></svg>
        </button>
        <button type="button" class="editor-btn" data-img-cmd="align" data-img-val="center" title="Centralizar">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14"/></svg>
        </button>
        <button type="button" class="editor-btn" data-img-cmd="align" data-img-val="right" title="Alinhar à direita">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14"/></svg>
        </button>
    `

    el.addEventListener('mousedown', e => {
        const btn = e.target.closest('[data-img-cmd]')
        if (!btn) return
        e.preventDefault()

        const cmd = btn.dataset.imgCmd
        const val = btn.dataset.imgVal

        editor.chain().focus().updateAttributes('image', {
            style: buildImageStyle(
                cmd === 'width' ? val : getCurrentWidth(editor),
                cmd === 'align' ? val : getCurrentAlign(editor),
            ),
        }).run()
    })

    wrapper.appendChild(el)
    return el
}


function positionImageToolbar(editor, wrapper, toolbar) {
    const { from } = editor.state.selection
    let imgDOM = editor.view.nodeDOM(from)
    if (!imgDOM) return

    const img = imgDOM.tagName === 'IMG' ? imgDOM : imgDOM.querySelector('img')
    if (!img) return

    const imgRect     = img.getBoundingClientRect()
    const wrapperRect = wrapper.getBoundingClientRect()

    toolbar.style.top  = `${imgRect.bottom - wrapperRect.top + wrapper.scrollTop + 6}px`
    toolbar.style.left = `${Math.max(0, imgRect.left - wrapperRect.left)}px`
}

function syncImageToolbarState(toolbar, attrs) {
    const curWidth = getCurrentWidthFromStyle(attrs.style)
    const curAlign = getCurrentAlignFromStyle(attrs.style)

    toolbar.querySelectorAll('[data-img-cmd="width"]').forEach(btn => {
        btn.classList.toggle('bg-gray-200', btn.dataset.imgVal === curWidth)
        btn.classList.toggle('text-gray-900', btn.dataset.imgVal === curWidth)
    })
    toolbar.querySelectorAll('[data-img-cmd="align"]').forEach(btn => {
        btn.classList.toggle('bg-gray-200', btn.dataset.imgVal === curAlign)
        btn.classList.toggle('text-gray-900', btn.dataset.imgVal === curAlign)
    })
}

// ─── Helpers de estilo ───────────────────────────────────────────────────────

function buildImageStyle(width, align) {
    const parts = []

    if (width) {
        parts.push(`width: ${width}`)
    }

    if (align === 'left') {
        parts.push('float: left; margin-right: 1rem; margin-bottom: 0.5rem')
    } else if (align === 'right') {
        parts.push('float: right; margin-left: 1rem; margin-bottom: 0.5rem')
    } else if (align === 'center') {
        parts.push('display: block; margin-left: auto; margin-right: auto; float: none')
    }

    return parts.length ? parts.join('; ') : null
}

function getCurrentWidth(editor) {
    return getCurrentWidthFromStyle(editor.getAttributes('image').style)
}

function getCurrentAlign(editor) {
    return getCurrentAlignFromStyle(editor.getAttributes('image').style)
}

function getCurrentWidthFromStyle(style) {
    if (!style) return null
    const m = style.match(/width:\s*([^;]+)/)
    return m ? m[1].trim() : null
}

function getCurrentAlignFromStyle(style) {
    if (!style) return null
    if (style.includes('float: left'))          return 'left'
    if (style.includes('float: right'))         return 'right'
    if (style.includes('margin-left: auto'))    return 'center'
    return null
}

// ─── Toolbar principal ───────────────────────────────────────────────────────

function handleCommand(cmd, editor, fileInput) {
    const chain = editor.chain().focus()

    if (cmd === 'image') {
        fileInput?.click()
        return
    }

    if (cmd === 'link') {
        if (editor.isActive('link')) {
            chain.unsetLink().run()
        } else {
            const url = prompt('URL do link:')
            if (url) chain.setLink({ href: url, target: '_blank' }).run()
        }
        return
    }

    const commands = {
        bold:       () => chain.toggleBold().run(),
        italic:     () => chain.toggleItalic().run(),
        strike:     () => chain.toggleStrike().run(),
        code:       () => chain.toggleCode().run(),
        h2:         () => chain.toggleHeading({ level: 2 }).run(),
        h3:         () => chain.toggleHeading({ level: 3 }).run(),
        blockquote: () => chain.toggleBlockquote().run(),
        bullet:     () => chain.toggleBulletList().run(),
        ordered:    () => chain.toggleOrderedList().run(),
        hr:         () => chain.setHorizontalRule().run(),
        undo:       () => chain.undo().run(),
        redo:       () => chain.redo().run(),
    }

    commands[cmd]?.()
}

function updateActiveButtons(editor, wrapper) {
    const activeMap = {
        bold:       editor.isActive('bold'),
        italic:     editor.isActive('italic'),
        strike:     editor.isActive('strike'),
        code:       editor.isActive('code'),
        h2:         editor.isActive('heading', { level: 2 }),
        h3:         editor.isActive('heading', { level: 3 }),
        blockquote: editor.isActive('blockquote'),
        bullet:     editor.isActive('bulletList'),
        ordered:    editor.isActive('orderedList'),
        link:       editor.isActive('link'),
    }

    wrapper.querySelectorAll('[data-cmd]').forEach(btn => {
        const cmd = btn.dataset.cmd
        if (cmd in activeMap) {
            btn.classList.toggle('bg-gray-200', activeMap[cmd])
            btn.classList.toggle('text-gray-900', activeMap[cmd])
        }
    })
}
