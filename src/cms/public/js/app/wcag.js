/*
 * Corrections to Filament's tooltips that are needed to meet WCAG 2.2 level AA.
 */
const TOOLTIP_SELECTOR = '[x-tooltip], [x-tooltip\\.html]'

// Make tooltips hoverable so they stay visible while the pointer moves over them
const makeTooltipHoverable = (element) => {
    if (!element || element.dataset.wcagTooltip === 'hoverable' || !element.__x_tippy) {
        return
    }

    element.__x_tippy.setProps({ interactive: true })
    element.dataset.wcagTooltip = 'hoverable'
}

// Set tooltips to be hoverable on mouseover and focusin events
document.addEventListener(
    'mouseover',
    (event) => makeTooltipHoverable(event.target?.closest?.(TOOLTIP_SELECTOR)),
    true,
)

// Escape closes the tooltip
window.addEventListener(
    'keydown',
    (event) => {
        if (event.key !== 'Escape') {
            return
        }

        const element = [...document.querySelectorAll('[aria-describedby*="tippy-"]')].find(
            (candidate) => candidate.__x_tippy?.state?.isVisible,
        )

        if (!element) {
            return
        }

        element.__x_tippy.hide()
        event.stopImmediatePropagation()
        event.stopPropagation()
    },
    true,
)

/*
 * A new repeater item (f.e. 'AVG Doelen') is inserted above the add button, while the tab order continues
 * below it, so the new fields are behind the keyboard user. Moving the focus into the new item restores
 * the focus order.
 */
document.addEventListener('livewire:init', () => {
    Livewire.on('repeater-item-added', ({ statePath }) => {
        const items = document
            .querySelector(`[data-state-path="${statePath}"]`)
            ?.querySelectorAll('.fi-fo-repeater-item')

        items?.[items.length - 1]
            ?.querySelector(
                '.fi-fo-repeater-item-content textarea, .fi-fo-repeater-item-content input:not([type="hidden"]), .fi-fo-repeater-item-content select',
            )
            ?.focus()
    })
})

document.addEventListener('click', (event) => {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey) {
        return
    }

    if (event.target.closest('a, button, input, label, select, textarea, [role="button"]')) {
        return
    }

    if (window.getSelection()?.toString()) {
        return
    }

    event.target.closest('.fi-ta-row')?.querySelector('[data-row-target]')?.click()
})

// FilePond's "Bladeren" is made interactive with a bare tabindex; the role has to say it acts as a button
const patchFilePondBrowse = (node) => {
    if (node.matches?.('.filepond--label-action')) {
        node.setAttribute('role', 'button')
    }

    node.querySelectorAll?.('.filepond--label-action').forEach((span) => span.setAttribute('role', 'button'))
}

// A modal points aria-labelledby at "{id}.heading", but no element is ever given that id
const labelModalHeading = (dialog) => {
    const labelId = dialog.getAttribute('aria-labelledby')
    const heading = dialog.querySelector('.fi-modal-heading')

    if (heading && !document.getElementById(labelId)) {
        heading.id = labelId
    }
}

const patchModalHeadings = (node) => {
    const dialog = node.closest?.('[role="dialog"][aria-labelledby]')

    if (dialog) {
        labelModalHeading(dialog)
    }

    node.querySelectorAll?.('[role="dialog"][aria-labelledby]').forEach(labelModalHeading)
}

patchFilePondBrowse(document.body)
patchModalHeadings(document.body)

// Watch for new mutations and apply patches
new MutationObserver((mutations) => {
    for (const { addedNodes } of mutations) {
        for (const node of addedNodes) {
            if (node instanceof HTMLElement) {
                patchFilePondBrowse(node)
                patchModalHeadings(node)
            }
        }
    }
}).observe(document.body, { childList: true, subtree: true })

/*
 * A validation error is rendered as a plain paragraph next to the field: nothing marks the field invalid
 * and nothing ties the message to it, so screen readers announce neither.
 */
let fieldErrorCount = 0

const setFieldError = (field, errorId) => {
    const describedBy = (field.getAttribute('aria-describedby') ?? '')
        .split(/\s+/)
        .filter((id) => id && id !== field.dataset.wcagErrorId)

    if (errorId) {
        describedBy.push(errorId)
        field.dataset.wcagErrorId = errorId
        field.setAttribute('aria-invalid', 'true')
    } else {
        delete field.dataset.wcagErrorId
        field.removeAttribute('aria-invalid')
    }

    if (describedBy.length > 0) {
        field.setAttribute('aria-describedby', describedBy.join(' '))
    } else {
        field.removeAttribute('aria-describedby')
    }
}

const syncFieldErrors = () => {
    for (const wrapper of document.querySelectorAll('[data-field-wrapper]')) {
        const error = wrapper.querySelector('[data-validation-error]')
        const ownError = error?.closest('[data-field-wrapper]') === wrapper ? error : null

        if (ownError) {
            ownError.id ||= `wcag-field-error-${++fieldErrorCount}`
        }

        for (const field of wrapper.querySelectorAll('input:not([type="hidden"]), select, textarea')) {
            if (field.closest('[data-field-wrapper]') !== wrapper) {
                continue
            }

            if (ownError || field.dataset.wcagErrorId) {
                setFieldError(field, ownError?.id ?? null)
            }
        }
    }
}

document.addEventListener('livewire:init', () => {
    syncFieldErrors()
    Livewire.hook('morphed', () => syncFieldErrors())
})

// Filament only scrolls to the first validation error; moving the focus there announces it as well
window.addEventListener('form-validation-error', (event) => {
    requestAnimationFrame(() => {
        const component = document.querySelector(`[wire\\:id="${event.detail?.livewireId}"]`) ?? document

        component
            .querySelector('[data-validation-error]')
            ?.closest('[data-field-wrapper]')
            ?.querySelector('input:not([type="hidden"]), select, textarea')
            ?.focus()
    })
})

/*
 * A dropdown trigger has no aria-expanded, so the state of the user menu, the filters and the column
 * toggle is not announced.
 */
const trackedDropdownPanels = new WeakSet()

const trackDropdownState = (dropdown) => {
    const trigger = dropdown.querySelector(':scope > .fi-dropdown-trigger')
    const panel = dropdown.querySelector(':scope > .fi-dropdown-panel')

    if (!trigger || !panel || trackedDropdownPanels.has(panel)) {
        return
    }

    const control = trigger.querySelector('button, a, [role="button"]')

    if (!control) {
        return
    }

    // The state lives in a property of Filament's float plugin; the Alpine scope only holds methods.
    const syncState = () => control.setAttribute('aria-expanded', panel._x_isShown === true ? 'true' : 'false')

    syncState()
    new MutationObserver(syncState).observe(panel, { attributes: true, attributeFilter: ['style'] })
    trackedDropdownPanels.add(panel)
}

const trackDropdownStates = (root = document) => root.querySelectorAll?.('.fi-dropdown').forEach(trackDropdownState)

document.addEventListener('livewire:init', () => {
    trackDropdownStates()
    Livewire.hook('morphed', ({ el }) => trackDropdownStates(el))
})
