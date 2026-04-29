import './bootstrap';
import Sortable from 'sortablejs';

window.Sortable = Sortable;

document.addEventListener('alpine:init', () => {
    window.Alpine.directive('sortable', (el, { expression }, { evaluateLater, cleanup }) => {
        const onEnd = evaluateLater(expression);

        const instance = Sortable.create(el, {
            handle: '[data-drag-handle]',
            animation: 150,
            ghostClass: 'opacity-40',
            onEnd: () => {
                const ids = Array.from(el.querySelectorAll('[data-section-id]'))
                    .map(node => parseInt(node.dataset.sectionId, 10))
                    .filter(Boolean);
                onEnd(() => {}, { scope: { $ids: ids } });
            },
        });

        cleanup(() => instance.destroy());
    });
});
