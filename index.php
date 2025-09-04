<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4.works teste loja</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <style type="text/tailwindcss">
        input[type=text], input[type=email], input[type=tel], textarea{
        @apply border border-solid border-gray-300 border-black rounded-lg px-3 py-2
      }

      input[type=submit]{
        @apply bg-green-500 hover:bg-green-600 px-5 py-2 text-white rounded-lg
      }
    </style>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="font-bold text-3xl text-center mt-5 uppercase">Frutas</h1>

        <form id="form-add" class="bg-white rounded-xl shadow p-6 mb-8 flex flex-col sm:flex-row gap-4 items-center justify-center">
            <input type="text" name="nome" placeholder="Nome da fruta" required class="flex-1 min-w-[120px]" />
            <input type="text" id="maskMoney" name="preco" placeholder="Preço" step="0.01" min="0" required class="flex-1 min-w-[80px]" />
            <input type="submit" value="Adicionar" class="shadow cursor-pointer transition-colors" />
        </form>

        <div class="grid mt-5" id="grid">
            <?php if (!$frutas): ?>
                <div class="card">Nenhuma fruta encontrada.</div>
            <?php else: ?>
                <?php foreach ($frutas as $f): ?>
                    <div class="card py-5 bg-gray-100">
                        <div class="title text-lg bold"><?= htmlspecialchars($f->nome) ?></div>
                        <div class="price text-green-600">
                            <?= number_format((float)$f->preco, 2, ',', '.') ?> <small>R$</small>
                        </div>
                        <div class="meta text-sm italic">
                            #<?= (int)$f->id ?> • Criada em: <?= date('d/m/Y H:i', strtotime($f->created_at)) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <script>
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
            $('.remover-btn').click(function() {
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
                    success: function(resp) {
                        if (resp && resp.ok) {
                            $card.remove();
                        } else {
                            alert('Erro ao remover fruta.');
                        }
                    },
                    error: function() {
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
                success: function(resp) {
                    if (!resp || resp.ok !== true) {
                        $('.grid').html('<div class="empty">Erro: resposta inválida.</div>');
                        return;
                    }
                    render(resp.frutas || []);
                },
                error: function(xhr) {
                    const msg = xhr?.responseJSON?.error || xhr?.statusText || 'Falha ao listar';
                    $('.grid').html(`<div class="empty">Erro: ${escapeHtml(msg)}</div>`);
                }
            });
        }

        $('#form-add').submit(function(e) {
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
                success: function(resp) {
                    if (resp && resp.ok) {
                        $('#form-add')[0].reset();
                        load();
                    } else {
                        alert(resp.error || 'Erro ao adicionar fruta.');
                    }
                },
                error: function() {
                    alert('Erro ao adicionar fruta.');
                }
            });
        });

        $(function() {
            $('#maskMoney').mask('000.000.000,00', {
                reverse: true
            });
            load();
        });

        $(load);
    </script>
</body>

</html>