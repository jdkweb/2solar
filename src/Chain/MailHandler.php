<?php

namespace TwoSolar\Chain;

use TwoSolar\Chain\Traits\Mailer;
use TwoSolar\Chain\Traits\Richmailer;
use TwoSolar\Chain\Traits\Zapier;

class MailHandler extends Chain
{
    use Mailer;
    use Zapier;
    use Richmailer;

    public function initial(array $rows):bool
    {
        // Logger
        $this->twoSolar->logger->info("[".__METHOD__."] Scan op status_id: ". $this->twoSolar->status_id.":".$this->twoSolar->status . ";");
        // count data before
        return (count($rows) > 0);
    }

    //----------------------------------------------------------------------------------------

    /**
     * @param array $data
     * @return array
     */
    public function run(array $data): array
    {
        // get options for this status (subject en te versturen mailadressen)
        $options = json_decode($data['status_data']['options'], true);
        // FROM TRAITS get mailhandler method for this status
        $handler = "set".ucfirst($data['status_data']['mail_handler'])."Chunk";

        $success_ids = [];
        foreach ($data['items'] as $key => $item) {
            // check if method (traits) exists
            if (!method_exists($this, $handler)) {
                continue;
            }
            // add options to data
            $item['options'] = $options;
            // trait method uitvoeren => settings for mailer
            if (!$this->$handler($item)) {
                continue;
            }

            // MAIL
            $this->twoSolar->mailer->config();
            if (!$this->twoSolar->mailer->send()) {
                continue;
            }
            // Success mail
            $success_ids[] = $item['request_id'];
            $this->twoSolar->logger->info("[".__METHOD__."] Item verzonden status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status . " | request_id: ".$item['request_id']);
            // 5sec pauze for mail
            if (!DEBUG_SEND_NO_MAIL) {
                sleep(5);
            }
        }

        if (empty($success_ids)) {
            $this->twoSolar->logger->info("[".__METHOD__."] Geen success items status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status);
        }

        return $success_ids;
    }

    //---------------------------------------------------------------------------------------

    /**
     * USED IN TRAITS
     * load thml template for email
     * @param string $name
     * @param array $data
     * @return string
     */
    private function getChunk(string $name, array $data): string
    {
        $file_path = dirname(dirname(__DIR__)) ."/chunks/".$name.".tpl";

        if (!file_exists($file_path)) {
            return "file not found: " .$name;
        }

        $arr = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $arr["{".strtoupper($key)."}"] = $value;
        }

        $text = file_get_contents($file_path);

        return str_replace(array_keys($arr), array_values($arr), $text);
    }
}
