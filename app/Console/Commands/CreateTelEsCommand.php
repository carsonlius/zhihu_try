<?php

namespace App\Console\Commands;

use App\Model\MongoCrawlerTel;
use Illuminate\Console\Command;

class CreateTelEsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:tel_es';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成索引文档';

    /** @var string 索引名字 */
    private $index = 'tel';

    private $bar;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->_handleDo();
        } catch (\Exception $e) {
            $this->output->error($e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 操作
     */
    private function _handleDo()
    {
        // 当前的总数
        $count = MongoCrawlerTel::count();
        $this->bar = $this->output->createProgressBar($count);

        // 建立索引
        $this->_createIndex();

        // 生成索引文档
        $this->_createDocument();

    }

    /**
     * 生成索引文档
     */
    private function _createDocument()
    {
        $this->output->success('开始文档索引工作');
        $option = [
//            'batchSize' => 10000
        ];
        $cursor = \DB::connection('mongodb_backend')->collection('crawler_tels')->raw(function ($collection) use ($option){
            return $collection->find([], $option);
        });

        $params = ['body' => []];
        $i = 0;
        foreach ($cursor as $item) {
            $i++;

            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_id'    => (string)$item->_id,
                ]
            ];

            $params['body'][] = [
                'apikey' => $item->apikey,
                'tel' => $item->tel,
                'product_id' => $item->product_id,
                'amount_date' => $item->amount_date,
                'crawler' => $item->crawler,
                'operator' => $item->operator,
                'hash_key' => $item->hash_key,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];

            // Every 1000 documents stop and send the bulk request
            if ($i % 3000 == 0) {
                $responses = \Elasticsearch::bulk($params);

                // erase the old bulk request
                $params = ['body' => []];

                // unset the bulk response when you are done to save memory
                unset($responses);
            }
            $this->bar->advance();
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = \Elasticsearch::bulk($params);
        }

        $this->bar->finish();
        $this->output->success('完成文档索引工作');
    }

    /**
     * 建立索引
     */
    private function _createIndex()
    {
        $this->output->success('生成索引');
        $params = [
            'index' => $this->index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ]
            ]
        ];



//        \Elasticsearch::indices()->delete(['index' => $this->index]);
        \Elasticsearch::indices()->create($params);
    }
}
