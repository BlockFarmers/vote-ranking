(function () {
    'use strict';

    function findTarget(selector) {
        if (selector) {
            try {
                const element = document.querySelector(selector);

                if (element) {
                    return element;
                }
            } catch (error) {
                console.warn('[Vote Ranking] Invalid CSS selector: ' + selector);
            }
        }

        const voteCard = document.getElementById('vote-card');

        if (!voteCard) {
            return null;
        }

        return voteCard.closest('.card') || voteCard;
    }

    function integrate() {
        const template = document.getElementById('vote-ranking-template');

        if (!template || document.querySelector('[data-vote-ranking]')) {
            return;
        }

        const target = findTarget(template.dataset.selector);

        if (target) {
            target.after(template.content.cloneNode(true));
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', integrate);
    } else {
        integrate();
    }
})();
