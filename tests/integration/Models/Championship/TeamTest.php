<?php
/**
 * RoleTest
 *
 * Created 2/18/16 2:51 PM
 * Test roles model
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Integration\Model
 * @subpackage Subpackage
 */

namespace Tests\Integration\Models\Championship;

use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class TeamTest extends \TestCase
{

    use DatabaseTransactions, DatabaseMigrations;

    /**
     * @var
     */
    protected $faker;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->resetEventListeners('App\Models\Championship\Team');

    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     *
     * Test to see when getting a team we
     * get back the correct name attribute
     *
     * @test
     */
    public function it_has_a_name_attribute()
    {

        $name = $this->faker->name;
        $item = factory(Team::class)->create(['name' => $name, 'tournament_id' => factory(Tournament::class)->create()->id]);
        $getName = Team::find($item->id);

        $this->assertSame($name, $getName->name);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the correct emblem attribute
     *
     * @test
     */
    public function it_has_a_emblem_attribute()
    {
        $imageUrl = $this->faker->imageUrl();
        $item = factory(Team::class)->create(['emblem' => $imageUrl]);

        $getName = Team::find($item->id);

        $this->assertSame($imageUrl, $getName->emblem);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the game the team is playing
     *
     * @test
     */
    public function it_has_a_tournament_attribute()
    {
        $tournament = factory(Tournament::class)->create();
        $item = factory(Team::class)->create(['tournament_id' => $tournament->id]);

        $getGame = Team::find($item->id);

        $this->assertSame($getGame->tournament->id, $tournament->id);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the game the captain of the team
     *
     * @test
     */
    public function it_has_a_captain_attribute()
    {
        $item = factory(Team::class)->create();
        $player = factory(Player::class)->create([]);
        $player::createRelation(['player' => $player, 'team' => $item]);
        $item->captain = $player->id;
        $item->save();
        
        $getTeam = Team::find($item->id);
        $this->assertSame($getTeam->captain()->id, $player->id);
    }

    /**
     *
     * Test to see when getting a team we
     * get false when captain player id doesn't exist
     *
     * @test
     */
    public function it_has_a_captain_attribute_but_player_is_missing()
    {
        $item = factory(Team::class)->create();
        $item->save();

        $this->assertFalse($item->captain());
    }

    /**
     *
     * Test to see when getting a Team we
     * get back the correct updated by attribute
     *
     * @test
     */
    public function it_has_a_updated_by_attribute()
    {
        $user_name = $this->faker->userName;
        $user = factory(\App\Models\WpUser::class)->create(['user_login' => $user_name]);
        $team = factory(Team::class)->create(['updated_by' => $user->ID]);
        $getTeam = Team::find($team->id);
        $this->assertSame($user->ID, $getTeam->updated_by);
    }

    /**
     *
     * Test to see when getting a Team we
     * get back the correct updated on attribute
     *
     * @test
     */
    public function it_has_a_updated_on_attribute()
    {
        $time_stamp = Carbon::now("CMT");
        $team = factory(Team::class)->create(['updated_on' => $time_stamp]);
        $this->assertSame($time_stamp, $team->updated_on);
    }

    /**
     * @test
     */
    public function it_unattached_its_players_when_deleted()
    {
        $team = factory(Team::class)->create();
        $team_id = $team->id;
        $players = factory(\App\Models\Championship\Player::class, 5)->create();
        for($i=0; $i < count($players); $i++) {
            $p = $players[$i]->toArray();
            if(is_array($p['id'])){dd($p['id']);}
            if(is_array($team_id)){dd($team_id);}
            \App\Models\Championship\Player::createRelation(['player' => $p['id'], 'team' => $team_id]);
        }
        $team->delete();
        foreach ($players as $player) {
            $this->assertEmpty($player->teams);
        }
    }
}
