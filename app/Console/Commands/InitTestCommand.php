<?php

namespace App\Console\Commands;

use App\BookClosureClassification;
use App\BookClosureRelationship;
use App\PathTree;
use Illuminate\Console\Command;
use Ultraware\Roles\Models\Permission;

class InitTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $bar;

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
        } catch (\Error $e) {
            $this->output->error($e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    private function _handleDo()
    {
        $counter = PathTree::where([])->count();

        // 初始化
        $this->_initData();

        // 添加关系
        $this->bar = $this->output->createProgressBar($counter);
        PathTree::getItems([], ['name', 'path'])->each(function($path_item){

            // 增加关联关系ancestor_id
            $this->_appendRelationShip($path_item);

            $this->bar->advance();
        });
        $this->bar->finish();
    }

    private function _initData()
    {
        PathTree::getItems([])->each(function($path){
            $name = $path->name;
            BookClosureClassification::create(compact('name'));
        });
    }

    /**
     * 关联关系
     * @param PathTree $path_base
     */
    private function _appendRelationShip(PathTree $path_base)
    {
        // 子孙级别
        $list_sub = $this->_getChirdrenNode($path_base->path);

        $book_base = $this->_getBookClassificationByName($path_base->name);

        collect($list_sub)->each(function($path_item) use ($path_base, $book_base){
            list($length_item, $length_base) = [
                count(explode('/', $path_item->path)),
                count(explode('/', $path_base->path)),
            ];

            $book_item = $this->_getBookClassificationByName($path_item->name);
            list($ancestor_id,$descendant_id, $distance) = [
                $book_base->id,
                $book_item->id,
                $length_item - $length_base
            ];
            BookClosureRelationship::create(compact('ancestor_id', 'descendant_id', 'distance'));
        });
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function _getBookClassificationByName(string $name)
    {
        return BookClosureClassification::getOneItem(compact('name'));
    }

    /**
     * 获取所有的子级节点
     * @param string $path
     * @return array
     */
    private function _getChirdrenNode(string $path) : array
    {
        $sql_sub = 'select * from path_tree where path like "' . $path . '%"';
        return $list_chirlds = \DB::select($sql_sub);
    }
}
