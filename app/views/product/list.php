<?php if (isset($_GET['status'])) : ?>
    <div class="row justify-content-center my-5">
        <?php switch ($_GET['status']):
            case 'ok_edit': ?>
                <div class="alert alert-success text-center col-md-5" role="alert">
                    Редагування успішне
                </div>
            <?php break;
            case 'ok_add': ?>
                <div class="alert alert-success text-center col-md-5" role="alert">
                    Додавання успішне
                </div>
            <?php break;
            case 'ok_delete': ?>
                <div class="alert alert-success text-center col-md-5" role="alert">
                    Видалення успішне
                </div>
            <?php break;
            case 'no_delete': ?>
                <div class="alert alert-danger text-center col-md-5" role="alert">
                    Такого товару не існує
                </div>
            <?php break;
            default: ?>
                <?php break ?>
        <?php endswitch ?>
    </div>
<?php endif ?>

<form class="my-4" method="POST" action="<?= Core\Route::getBasePath() . $_SERVER['REQUEST_URI'] ?>">
    <div class="row justify-content-between">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-select" name='sortfirst'>
                        <option <?php echo filter_input(INPUT_POST, 'sortfirst') === 'price_ASC' ? 'selected' : ''; ?> value="price_ASC">від дешевших до дорожчих</option>
                        <option <?php echo filter_input(INPUT_POST, 'sortfirst') === 'price_DESC' ? 'selected' : ''; ?> value="price_DESC">від дорожчих до дешевших</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-select" name='sortsecond'>
                        <option <?php echo filter_input(INPUT_POST, 'sortsecond') === 'qty_ASC' ? 'selected' : ''; ?> value="qty_ASC">по зростанню кількості</option>
                        <option <?php echo filter_input(INPUT_POST, 'sortsecond') === 'qty_DESC' ? 'selected' : ''; ?> value="qty_DESC">по спаданню кількості</option>
                    </select>
                </div>
            </div>
        </div>
        <input name="sortproduct" class="btn-submit btn btn-dark col-md-3" type="submit" value="Сортувати">
    </div>
</form>

<div class="product">
    <p class="text-center my-3 row justify-content-center">
        <span class="btn-add col-md-3">
            <?= \Core\Url::getLink('/product/add', 'Додати товар +'); ?>
        </span>
    </p>
</div>

<?php

$products =  $this->get('products');

foreach ($products as $product) :
?>

    <div class="product">
        <p class="sku">Код: <?php echo $product['sku'] ?></p>
        <h4><?php echo $product['name'] ?><h4>
                <p> Ціна: <span class="price"><?php echo $product['price'] ?></span> грн</p>
                <p> Кількість: <?php echo $product['qty'] ?></p>
                <p><?php if (!$product['qty'] > 0) {
                        echo 'Нема в наявності';
                    } ?></p>
                <div class="wrapper-links row">
                    <div class="edit-link col-md-2">
                        <?= \Core\Url::getLink('/product/edit', 'Редагувати', array('id' => $product['id'])); ?>
                    </div>
                    <div class="delete-link col-md-2">
                        <?= \Core\Url::getLink('/product/delete', 'Видалити', array('id' => $product['id'])); ?>
                    </div>
                </div>
    </div>
<?php endforeach; ?>