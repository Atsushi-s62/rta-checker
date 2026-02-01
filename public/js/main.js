'use strict';
{
    function sortByViewers(containerId) {
        const container = document.querySelector(`#${containerId}`);
        
        // .listクラスの持つ要素を配列に変換
        const items = Array.from(container.querySelectorAll('.list'));

        items.sort((a, b) => {

            // 視聴数の数字を取得
            const textA = a.querySelector('.viewers').textContent;
            const textB = b.querySelector('.viewers').textContent;

            // 数字以外を除去
            const countA = parseInt(textA.replace(/[^\d]/g, '')) || 0;
            const countB = parseInt(textB.replace(/[^\d]/g, '')) || 0;

            return countB - countA;
        });

        items.forEach(item => container.appendChild(item));
    }

    // 実行例：ページ読み込み時やボタンクリック時に実行
    window.addEventListener('DOMContentLoaded', () => {
        sortByViewers('rta-list');
        sortByViewers('others');
    });
}