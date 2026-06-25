<?php

namespace App\Controllers;

use App\Models\Quotation;

class QuotationController {
    public function list() {
        header('Content-Type: application/json');
        
        $agencyId = $_GET['agency_id'] ?? null;
        $startDate = $_GET['start_date'] ?? date('Y-m-d');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        if (!$agencyId) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Agency ID is required"]);
            return;
        }

        try {
            $model = new Quotation();
            $list = $model->list($startDate, $endDate, $agencyId);

            echo json_encode([
                "status" => "success",
                "data" => $list
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
            return;
        }

        try {
            $model = new Quotation();
            $id = $model->create($input);

            echo json_encode([
                "status" => "success",
                "message" => "Quotation created successfully",
                "data" => ["id" => $id]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function show() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID is required"]);
            return;
        }

        try {
            $model = new Quotation();
            $data = $model->getDetails($id);

            if ($data) {
                echo json_encode(["status" => "success", "data" => $data]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Quotation not found"]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function update() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$id || !$input) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID and valid JSON input are required"]);
            return;
        }

        try {
            $model = new Quotation();
            $model->update($id, $input);

            echo json_encode([
                "status" => "success",
                "message" => "Quotation updated successfully"
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}
