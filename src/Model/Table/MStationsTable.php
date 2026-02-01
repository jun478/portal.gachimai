<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MStations Model
 *
 * @method \App\Model\Entity\MStation newEmptyEntity()
 * @method \App\Model\Entity\MStation newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\MStation> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MStation get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\MStation findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\MStation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\MStation> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MStation|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\MStation saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\MStation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MStation>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\MStation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MStation> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\MStation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MStation>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\MStation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\MStation> deleteManyOrFail(iterable $entities, array $options = [])
 */
class MStationsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('m_stations');
        $this->setDisplayField('station_name');
        $this->setPrimaryKey('station_cd');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('station_g_cd')
            ->notEmptyString('station_g_cd');

        $validator
            ->scalar('station_name')
            ->maxLength('station_name', 256)
            ->notEmptyString('station_name');

        $validator
            ->scalar('station_name_k')
            ->maxLength('station_name_k', 256)
            ->allowEmptyString('station_name_k');

        $validator
            ->scalar('station_name_r')
            ->maxLength('station_name_r', 256)
            ->allowEmptyString('station_name_r');

        $validator
            ->integer('line_cd')
            ->notEmptyString('line_cd');

        $validator
            ->allowEmptyString('pref_cd');

        $validator
            ->scalar('post')
            ->maxLength('post', 32)
            ->allowEmptyString('post');

        $validator
            ->scalar('address')
            ->maxLength('address', 1024)
            ->allowEmptyString('address');

        $validator
            ->numeric('lon')
            ->allowEmptyString('lon');

        $validator
            ->numeric('lat')
            ->allowEmptyString('lat');

        $validator
            ->date('open_ymd')
            ->allowEmptyDate('open_ymd');

        $validator
            ->date('close_ymd')
            ->allowEmptyDate('close_ymd');

        $validator
            ->allowEmptyString('e_status');

        $validator
            ->integer('e_sort')
            ->allowEmptyString('e_sort');

        return $validator;
    }
}
