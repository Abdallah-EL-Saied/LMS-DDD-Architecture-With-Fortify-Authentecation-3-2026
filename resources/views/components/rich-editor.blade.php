@props(['label' => null, 'placeholder' => 'Start typing excellence...'])

@php
    $modelName = $attributes->wire('model')->value() ?? 'rich_editor_' . uniqid();
    $id = str_replace(['.', '$'], '_', $modelName);
@endphp

<div x-data="richEditor(@entangle($attributes->wire('model')), '{{ $placeholder }}', '#toolbar-{{ $id }}')" wire:ignore
    class="space-y-2">
    @if($label)
        <label
            class="block text-sm font-black text-zinc-900 dark:text-zinc-100 uppercase tracking-tighter">{{ $label }}</label>
    @endif

    <div
        class="w-full bg-white rounded-[28px] border-2 border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 shadow-2xl shadow-zinc-200/50 focus-within:border-primary transition-all overflow-visible">
        <!-- Custom Toolbar -->
        <div id="toolbar-{{ $id }}"
            class="flex flex-wrap items-center gap-1 p-3 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 rounded-t-[26px]">
            <!-- Headers -->
            <div class="flex items-center bg-zinc-100 dark:bg-zinc-800 rounded-xl p-1 gap-0.5">
                <button type="button"
                    class="ql-header px-2 py-1 rounded-lg hover:bg-white dark:hover:bg-zinc-700 transition-all font-black text-[10px]"
                    value="1" title="{{ __('Heading 1') ?? 'Heading 1' }}">H1</button>
                <button type="button"
                    class="ql-header px-2 py-1 rounded-lg hover:bg-white dark:hover:bg-zinc-700 transition-all font-black text-[10px]"
                    value="2" title="{{ __('Heading 2') ?? 'Heading 2' }}">H2</button>
                <button type="button"
                    class="ql-header px-2 py-1 rounded-lg hover:bg-white dark:hover:bg-zinc-700 transition-all font-black text-[10px]"
                    value="3" title="{{ __('Heading 3') ?? 'Heading 3' }}">H3</button>
                <button type="button"
                    class="ql-header px-2 py-1 rounded-lg hover:bg-white dark:hover:bg-zinc-700 transition-all font-black text-[10px]"
                    value="4" title="{{ __('Heading 4') ?? 'Heading 4' }}">H4</button>
                <button type="button"
                    class="ql-header px-2 py-1 rounded-lg hover:bg-white dark:hover:bg-zinc-700 transition-all font-black text-[10px]"
                    value="5" title="{{ __('Heading 5') ?? 'Heading 5' }}">H5</button>
                <button type="button"
                    class="ql-header px-2 py-1 rounded-lg hover:bg-white dark:hover:bg-zinc-700 transition-all font-black text-[10px]"
                    value="6" title="{{ __('Heading 6') ?? 'Heading 6' }}">H6</button>
            </div>

            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700 mx-1"></div>

            <!-- Marks -->
            <button type="button"
                class="ql-bold p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="{{ __('Bold') ?? 'Bold' }}">
                <i class="fa-solid fa-bold text-sm"></i>
            </button>
            <button type="button"
                class="ql-italic p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="{{ __('Italic') ?? 'Italic' }}">
                <i class="fa-solid fa-italic text-sm"></i>
            </button>
            <button type="button"
                class="ql-underline p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="{{ __('Underline') ?? 'Underline' }}">
                <i class="fa-solid fa-underline text-sm"></i>
            </button>

            <!-- Colors -->
            <select class="ql-color p-1 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="{{ __('Text Color') ?? 'Text Color' }}"></select>
            <select class="ql-background p-1 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="{{ __('Background Color') ?? 'Background Color' }}"></select>

            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700 mx-1"></div>

            <!-- Alignment -->
            <button type="button"
                class="ql-align p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" value=""
                title="{{ __('Align Left') ?? 'Align Left' }}">
                <i class="fa-solid fa-align-left text-sm"></i>
            </button>
            <button type="button"
                class="ql-align p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                value="center" title="{{ __('Align Center') ?? 'Align Center' }}">
                <i class="fa-solid fa-align-center text-sm"></i>
            </button>
            <button type="button"
                class="ql-align p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                value="right" title="{{ __('Align Right') ?? 'Align Right' }}">
                <i class="fa-solid fa-align-right text-sm"></i>
            </button>
            <button type="button"
                class="ql-align p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                value="justify" title="{{ __('Justify') ?? 'Justify' }}">
                <i class="fa-solid fa-align-justify text-sm"></i>
            </button>

            <!-- Direction -->
            <button type="button"
                class="ql-direction p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                value="rtl" title="{{ __('Text Direction') ?? 'Text Direction' }}">
                <i class="fa-solid fa-paragraph-rtl text-sm"></i>
            </button>

            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700 mx-1"></div>

            <!-- Lists -->
            <button type="button"
                class="ql-list p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                value="bullet" title="{{ __('Bullet List') ?? 'Bullet List' }}">
                <i class="fa-solid fa-list-ul text-sm"></i>
            </button>
            <button type="button"
                class="ql-list p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                value="ordered" title="{{ __('Ordered List') ?? 'Ordered List' }}">
                <i class="fa-solid fa-list-ol text-sm"></i>
            </button>

            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700 mx-1"></div>

            <!-- Links & Images -->
            <button type="button"
                class="ql-link p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="{{ __('Insert Link') ?? 'Insert Link' }}">
                <i class="fa-solid fa-link text-sm"></i>
            </button>
            <button type="button"
                class="ql-image p-2.5 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="{{ __('Insert Image') ?? 'Insert Image' }}">
                <i class="fa-solid fa-image text-sm"></i>
            </button>

            <div class="flex-1"></div>

            <!-- Clean -->
            <button type="button"
                class="ql-clean p-2.5 rounded-xl hover:bg-red-50 text-red-400 dark:hover:bg-red-900/20 transition-colors"
                title="{{ __('Clear Formatting') ?? 'Clear Formatting' }}">
                <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
        </div>

        <!-- Editor Container -->
        <div
            class="min-h-[300px] w-full bg-white dark:bg-zinc-900 cursor-text scrollbar-thin relative rounded-b-[26px]">
            <!-- Loading Overlay -->
            <div x-show="isUploading" x-cloak
                class="absolute inset-0 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-sm z-50 flex flex-col items-center justify-center gap-4 animate-fade-in">
                <div class="size-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <span class="text-xs font-black uppercase tracking-widest text-primary">Uploading Image...</span>
            </div>

            <div x-ref="quill"
                class="h-full prose prose-zinc lg:prose-xl dark:prose-invert max-w-none p-6 outline-none focus:outline-none min-h-[300px]">
            </div>
        </div>
    </div>
</div>

@once
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <!-- Image Resize Module -->
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
    <script>
        (function () {
            const registerRichEditor = () => {
                if (window.Alpine.data('richEditor')) return;

                Alpine.data('richEditor', (content, placeholder, toolbarId) => ({
                    quill: null,
                    content: content,
                    placeholder: placeholder,
                    toolbarId: toolbarId,
                    isUpdating: false,
                    isUploading: false,

                    init() {
                        if (typeof Quill === 'undefined') {
                            setTimeout(() => this.init(), 100);
                            return;
                        }

                        // Register Image Resize (once)
                        if (typeof ImageResize !== 'undefined' && !Quill.imports['modules/imageResize']) {
                            Quill.register('modules/imageResize', ImageResize.default || ImageResize);
                        }

                        this.quill = new Quill(this.$refs.quill, {
                            theme: 'snow',
                            modules: {
                                toolbar: {
                                    container: this.toolbarId,
                                    handlers: {
                                        image: () => this.selectLocalImage()
                                    }
                                },
                                imageResize: {
                                    displaySize: true,
                                    modules: ['Resize', 'DisplaySize', 'Toolbar']
                                }
                            },
                            placeholder: this.placeholder,
                        });

                        // Set initial content
                        if (this.content) {
                            this.quill.root.innerHTML = this.content;
                        }

                        // On content change
                        this.quill.on('text-change', () => {
                            this.isUpdating = true;
                            this.content = this.quill.root.innerHTML;
                            this.isUpdating = false;
                        });

                        // Watch for external content changes
                        this.$watch('content', (value) => {
                            if (!this.isUpdating && value !== this.quill.root.innerHTML) {
                                this.quill.root.innerHTML = value || '';
                            }
                        });

                        // Listen for server-side link insertion
                        window.addEventListener('editor-link-inserted', (e) => {
                            if (e.detail.target === this.toolbarId && e.detail.url) {
                                const range = this.quill.getSelection() || { index: this.quill.getLength() };
                                this.quill.insertEmbed(range.index, 'image', e.detail.url);
                                this.quill.setSelection(range.index + 1);
                                this.isUploading = false;
                            }
                        });
                    },

                    selectLocalImage() {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.click();

                        input.onchange = () => {
                            const file = input.files[0];
                            if (/^image\//.test(file.type)) {
                                this.uploadToServer(file);
                            }
                        };
                    },

                    uploadToServer(file) {
                        this.isUploading = true;
                        this.$wire.set('targetEditorId', this.toolbarId);
                        this.$wire.upload('editorUpload', file,
                            (uploadedUrl) => {
                                // Link insertion is handled by the event listener above
                            },
                            () => {
                                this.isUploading = false;
                                alert('Upload failed');
                            }
                        );
                    }
                }));
            };

            if (window.Alpine) {
                registerRichEditor();
            } else {
                document.addEventListener('alpine:init', registerRichEditor);
            }
        })();
    </script>
    <style>
        /* Modern UI Fixes for Quill */
        .ql-container.ql-snow {
            border: none !important;
            font-family: inherit !important;
            font-size: inherit !important;
        }

        .ql-editor {
            min-height: 250px;
            padding: 0 !important;
            cursor: text;
        }

        .ql-editor.ql-blank::before {
            left: 0 !important;
            font-style: italic;
            color: #adb5bd;
            opacity: 0.6;
            content: attr(data-placeholder);
            pointer-events: none;
        }

        .ql-active {
            background-color: #f4f4f5 !important;
            color: #7c3aed !important;
        }

        .dark .ql-active {
            background-color: #27272a !important;
            color: #a78bfa !important;
        }

        /* Alignment & Direction */
        .ql-align-center {
            text-align: center;
        }

        .ql-align-right {
            text-align: right;
        }

        .ql-align-justify {
            text-align: justify;
        }

        .ql-direction-rtl {
            direction: rtl;
            text-align: inherit;
        }

        /* Font Sizes */
        .ql-size-small {
            font-size: 0.75rem;
        }

        .ql-size-large {
            font-size: 1.5rem;
        }

        .ql-size-huge {
            font-size: 2.5rem;
            font-weight: 900;
        }

        /* Color Picker Styling */
        .ql-snow .ql-picker.ql-color-picker,
        .ql-snow .ql-picker.ql-background-picker {
            width: 28px;
        }

        .ql-snow .ql-picker-label {
            border: none !important;
            padding: 0 !important;
            display: flex;
            items-center;
            justify-center;
        }

        .dark .ql-snow .ql-stroke {
            stroke: #e4e4e7;
        }

        .dark .ql-snow .ql-fill {
            fill: #e4e4e7;
        }

        .dark .ql-snow .ql-picker-options {
            background-color: #18181b;
            border: 1px solid #27272a;
        }

        /* Image Display Fixes */
        .ql-editor img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
            display: inline-block;
            margin: 0.5rem 0;
        }

        /* Flowbite-like scrollbar */
        .ql-editor::-webkit-scrollbar {
            width: 5px;
        }

        .ql-editor::-webkit-scrollbar-track {
            background: transparent;
        }

        .ql-editor::-webkit-scrollbar-thumb {
            background: #e4e4e7;
            border-radius: 10px;
        }

        .dark .ql-editor::-webkit-scrollbar-thumb {
            background: #3f3f46;
        }

        /* Tooltip Visibility Fix */
        .ql-snow.ql-toolbar button:hover .ql-stroke,
        .ql-snow .ql-toolbar button:hover .ql-stroke {
            stroke: #7c3aed !important;
        }

        .ql-tooltip {
            z-index: 1000 !important;
            border-radius: 1rem !important;
            border: 2px solid #e4e4e7 !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1) !important;
        }
    </style>
@endonce