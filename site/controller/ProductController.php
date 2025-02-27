<?php
class ProductController
{
    // Hiển thị danh sách sản phẩm
    function index()
    {
        $conds = [];
        $sorts = [];
        $page = $_GET['page'] ?? 1;
        $item_per_page = 10;
        $productRepository = new ProductRepository();


        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();

        $category_id = $_GET['category_id'] ?? '';
        if ($category_id) {
            $conds = [
                'category_id' => [
                    'type' => '=',
                    'val' => $category_id //3
                ]
            ];
            // SELECT * FROM view_product WHERE category_id=3
        }

        //price-range=100000-200000
        $priceRange = $_GET['price-range'] ?? '';
        if ($priceRange) {
            $temp = explode('-', $priceRange);
            $start_price = $temp[0];
            $end_price = $temp[1];
            $conds = [
                'sale_price' => [
                    'type' => 'BETWEEN',
                    'val' => "$start_price AND $end_price" //3
                ]
            ];
            // SELECT * FROM view_product WHERE sale_price BETWEEN 500000 AND 1000000
            // price-range=1000000-greater
            if ($end_price == 'greater') {
                $conds = [
                    'sale_price' => [
                        'type' => '>=',
                        'val' => $start_price
                    ]
                ];
                // SELECT * FROM view_product WHERE sale_price >= 10000000
            }
        }

        // sort=price-desc 
        $sort = $_GET['sort'] ?? '';
        if ($sort) {
            $temp = explode('-', $sort);
            $dummyCol = $temp[0];
            $map = ['price' => 'sale_price', 'alpha' => 'name', 'created' => 'created_date'];
            $colName = $map[$dummyCol];
            $order = strtoupper($temp[1]); //desc => DESC
            $sorts = [$colName => $order];
            // SELECT * FROM view_product ORDER BY sale_price DESC
        }

        $productTotal = $productRepository->getByNumber($conds, $sorts);

        $totalPage = ceil($productTotal / $item_per_page); //xử lý sau

        $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);
        require 'view/product/index.php';
    }

    function detail()
    {
        $id = $_GET['id'];
        $productRepository = new ProductRepository();
        // lấy product tương ứng với mã sản phẩm
        $product = $productRepository->find($id);
        $conds = [
            // Lấy sản phẩm có cùng danh mục
            'category_id' => [
                'type' => '=',
                'val' => $product->getCategoryId() //5
            ],
            // loại trừ đi sản phẩm đang xem
            'id' => [
                'type' => '!=',
                'val' => $product->getId() //3
            ]
        ];
        // SELECT * FROM view_product WHERE category_id = 5
        $relatedProducts = $productRepository->getBy($conds);

        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();
        $category_id = $product->getCategoryId();

        require 'view/product/detail.php';
    }
}
