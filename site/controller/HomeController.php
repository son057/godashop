<?php
class HomeController
{
    // Trang chủ
    function index()
    {
        $conds = [];
        $sorts = ['featured' => 'DESC'];
        $page = 1;
        $item_per_page = 4;
        $productRepository = new ProductRepository();

        // Lấy sản phẩm nổi bật
        $sorts = ['featured' => 'DESC'];
        $featuredProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

        // lấy sản phẩm mới nhất
        $sorts = ['created_date' => 'DESC'];
        $latestProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

        // lấy sản phẩm theo danh mục
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();

        // Duyệt từng danh mục để lấy tên danh mục và danh sách sản phẩm tương ứng
        // biến chứa cấu truc dữ liệu để gởi qua view
        $categoryProducts = [];
        foreach ($categories as $category) {
            $conds = [
                'category_id' => [
                    'type' => '=',
                    'val' => $category->getId() //3
                ]
            ];
            $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);
            // SELECT * FROM view_product WHERE category_id=3
            $categoryProducts[] = [
                'categoryName' => $category->getName(),
                'products' => $products
            ];
        }
        require 'view/home/index.php';
    }
}
