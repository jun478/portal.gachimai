<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Areas Model
 *
 * @method \App\Model\Entity\Area newEmptyEntity()
 * @method \App\Model\Entity\Area newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Area> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Area get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Area findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Area patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Area> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Area|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Area saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Area>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Area>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Area>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Area> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Area>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Area>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Area>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Area> deleteManyOrFail(iterable $entities, array $options = [])
 */
class AreasTable extends Table
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

        $this->setTable('areas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->integer('ken_id')
            ->allowEmptyString('ken_id');

        $validator
            ->integer('city_id')
            ->allowEmptyString('city_id');

        $validator
            ->integer('town_id')
            ->allowEmptyString('town_id');

        $validator
            ->scalar('zip')
            ->maxLength('zip', 8)
            ->allowEmptyString('zip');

        $validator
            ->boolean('office_flg')
            ->allowEmptyString('office_flg');

        $validator
            ->boolean('delete_flg')
            ->allowEmptyString('delete_flg');

        $validator
            ->scalar('ken_name')
            ->maxLength('ken_name', 8)
            ->allowEmptyString('ken_name');

        $validator
            ->scalar('ken_furi')
            ->maxLength('ken_furi', 8)
            ->allowEmptyString('ken_furi');

        $validator
            ->scalar('city_name')
            ->maxLength('city_name', 24)
            ->allowEmptyString('city_name');

        $validator
            ->scalar('city_furi')
            ->maxLength('city_furi', 24)
            ->allowEmptyString('city_furi');

        $validator
            ->scalar('town_name')
            ->maxLength('town_name', 32)
            ->allowEmptyString('town_name');

        $validator
            ->scalar('town_furi')
            ->maxLength('town_furi', 32)
            ->allowEmptyString('town_furi');

        $validator
            ->scalar('town_memo')
            ->maxLength('town_memo', 16)
            ->allowEmptyString('town_memo');

        $validator
            ->scalar('kyoto_street')
            ->maxLength('kyoto_street', 32)
            ->allowEmptyString('kyoto_street');

        $validator
            ->scalar('block_name')
            ->maxLength('block_name', 64)
            ->allowEmptyString('block_name');

        $validator
            ->scalar('block_furi')
            ->maxLength('block_furi', 64)
            ->allowEmptyString('block_furi');

        $validator
            ->scalar('memo')
            ->maxLength('memo', 255)
            ->allowEmptyString('memo');

        $validator
            ->scalar('office_name')
            ->maxLength('office_name', 255)
            ->allowEmptyString('office_name');

        $validator
            ->scalar('office_furi')
            ->maxLength('office_furi', 255)
            ->allowEmptyString('office_furi');

        $validator
            ->scalar('office_address')
            ->maxLength('office_address', 255)
            ->allowEmptyString('office_address');

        $validator
            ->integer('new_id')
            ->allowEmptyString('new_id');

        return $validator;
    }
}
