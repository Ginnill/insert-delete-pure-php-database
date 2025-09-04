function escapeHtml(text) {
    return $('<div/>').text(text).html();
}

function render(frutas) {
    if (!frutas.length) {
        $('.grid').html('<div class="card bg-white rounded-xl shadow p-6 flex flex-col items-center">Nenhuma fruta encontrada.</div>');
        return;
    }
    let html = frutas.map(f => `
                <div class="card bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 mb-6 flex flex-col gap-2 items-start sm:flex-row sm:items-center sm:justify-between" data-id="${escapeHtml(f.id)}">
                    <div class="flex-1">
                        <div class="title font-bold text-lg text-gray-800 mb-1">${escapeHtml(f.nome)}</div>
                        <div class="price text-green-600 text-xl font-semibold mb-1">
                            ${Number(f.preco).toFixed(2).replace('.', ',')} <small class="text-sm font-normal">R$</small>
                        </div>
                        <div class="meta text-sm italic text-gray-500">
                            #${parseInt(f.id)} • Criada em: ${escapeHtml(f.created_at)}
                        </div>
                    </div>
                    <input type="button" value="Remover" class="remover-btn bg-red-100 hover:bg-red-300 text-red-700 font-bold px-4 py-2 rounded-lg shadow transition-colors cursor-pointer mt-4 sm:mt-0">
                </div>
            `).join('');
    $('.grid').html(html);

    // Adiciona o handler de remoção
    $('.remover-btn').click(function () {
        const id = $(this).data('id');
        const $card = $(this).closest('.card');
        if (!confirm('Tem certeza que deseja remover esta fruta?')) return;
        $.ajax({
            url: 'api-list.php',
            method: 'POST',
            data: {
                id
            },
            dataType: 'json',
            success: function (resp) {
                if (resp && resp.ok) {
                    $card.remove();
                } else {
                    alert('Erro ao remover fruta.');
                }
            },
            error: function () {
                alert('Erro ao remover fruta.');
            }
        });
    });
}

function load() {
    $('.grid').html('<div class="empty">Carregando…</div>');
    $.ajax({
        url: 'api-list.php',
        method: 'GET',
        dataType: 'json',
        cache: false,
        success: function (resp) {
            if (!resp || resp.ok !== true) {
                $('.grid').html('<div class="empty">Erro: resposta inválida.</div>');
                return;
            }
            render(resp.frutas || []);
        },
        error: function (xhr) {
            const msg = xhr?.responseJSON?.error || xhr?.statusText || 'Falha ao listar';
            $('.grid').html(`<div class="empty">Erro: ${escapeHtml(msg)}</div>`);
        }
    });
}

$('#form-add').submit(function (e) {
    e.preventDefault();
    const nome = $(this).find('[name=nome]').val().trim();
    let preco = $(this).find('[name=preco]').val().trim();
    if (!nome || !preco) return alert('Preencha todos os campos!');
    preco = preco.replace(/\./g, '').replace(',', '.'); // Converte para formato float
    $.ajax({
        url: 'api-list.php',
        method: 'POST',
        data: { nome, preco },
        dataType: 'json',
        success: function (resp) {
            if (resp && resp.ok) {
                $('#form-add')[0].reset();
                load();
            } else {
                alert(resp.error || 'Erro ao adicionar fruta.');
            }
        },
        error: function () {
            alert('Erro ao adicionar fruta.');
        }
    });
});

$(function () {
    $('#maskMoney').mask('000.000.000,00', {
        reverse: true
    });
    load();
});

$(load);
