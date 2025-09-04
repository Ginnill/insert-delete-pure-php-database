<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4.works teste loja</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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

        <script defer src="/js/jquery.min.js"></script>
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script defer src="/js/main.js"></script>

</body>

</html>