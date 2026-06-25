<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController {
    public function list() {
        header('Content-Type: application/json');
        
        $agencyId = $_GET['agency_id'] ?? null;
        $categoryId = $_GET['category_id'] ?? null;
        $search = $_GET['q'] ?? null;

        if (!$agencyId) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Agency ID is required"]);
            return;
        }

        try {
            $model = new Product();
            $list = $model->listByAgency($agencyId, $categoryId, $search);
            $globalPresentations = $model->getGlobalPresentations();

            echo json_encode([
                "status" => "success",
                "data" => $list,
                "meta" => [
                    "global_presentations" => $globalPresentations
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error", 
                "message" => $e->getMessage()
            ]);
        }
        exit;
    }

    public function categories() {
        header('Content-Type: application/json');
        $model = new Product();
        $list = $model->listCategories();

        echo json_encode([
            "status" => "success",
            "data" => $list
        ]);
    }

    public function detail() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        $agencyId = $_GET['agency_id'] ?? null;

        if (!$id || !$agencyId) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID and agency_id are required"]);
            return;
        }

        try {
            $model = new Product();
            $product = $model->getById($id, $agencyId);
            $globalPres = $model->getGlobalPresentations();

            if ($product) {
                echo json_encode([
                    "status" => "success",
                    "data" => $product,
                    "meta" => ["global_presentations" => $globalPres]
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Product not found"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}
