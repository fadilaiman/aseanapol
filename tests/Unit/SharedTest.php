<?php

namespace Tests\Unit;

use App\Support\Cms;
use App\Support\Shared;
use Tests\TestCase;

/** Read-only checks of the shared-facts registry and helper (never writes to the database). */
class SharedTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cms::forgetMemo();
    }

    public function test_registry_group_is_second_and_pages_exist(): void
    {
        $g = Cms::group('shared');
        $this->assertSame(2, $g['_group']['order']);
        $this->assertSame('Shared facts (used across pages)', $g['_group']['label']);
        foreach (['organisation', 'secretariat', 'members', 'roster', 'partners', 'dialogue'] as $slug) {
            $this->assertNotNull(Cms::page("shared.$slug"), $slug);
        }
    }

    public function test_default_member_rows(): void
    {
        $keys = array_column(Cms::fieldDef('shared.members', 'list.rows')['default'], 'key');
        $this->assertSame(['brunei', 'cambodia', 'indonesia', 'lao', 'malaysia', 'myanmar', 'philippines', 'singapore', 'thailand', 'vietnam', 'timor-leste'], $keys);
        $this->assertCount(11, array_unique($keys));
        $this->assertSame('Indonesian National Police (INP)', Shared::memberName('indonesia'));
        $this->assertSame('Philippine National Police (PNP)', Shared::memberName('philippines'));
        $this->assertSame('+856 213 16323', Shared::member('lao')['tel']);
        $this->assertSame('+673 245 9527', Shared::member('brunei')['fax']);
        $this->assertSame(['+673-2459 500', '+673-2423 901 ext: 784/785'], Shared::member('brunei')['tels']);
        $this->assertSame("\u{1F1F2}\u{1F1FE}", Shared::member('malaysia')['flag']);
    }

    public function test_missing_keys_are_null_not_exceptions(): void
    {
        $this->assertNull(Shared::member('atlantis'));
        $this->assertNull(Shared::memberName('atlantis'));
        $this->assertNull(Shared::partner('nope'));
        $this->assertNull(Shared::dialogueEntry('nope'));
        $this->assertNull(Shared::get('no-such-page', 'a.b'));
        $this->assertSame('x', Shared::get('no-such-page', 'a.b', 'x'));
        $this->assertSame([], Shared::all('no-such-page'));
        $this->assertNull(Shared::currentHolder('zzz'));
        $this->assertSame([], Shared::roster('zzz'));
    }

    public function test_secretariat_keeps_two_distinct_mailboxes(): void
    {
        $this->assertSame('aseanapolsec@aseanapol.org', Shared::secretariat('contact.email_secretariat'));
        $this->assertSame('info@aseanapol.org', Shared::secretariat('contact.email_general'));
        $this->assertSame(11, shared('organisation', 'counts.member_count_num'));
        $this->assertStringContainsString("\n", Shared::secretariat('contact.address'));
    }

    public function test_roster(): void
    {
        $this->assertCount(23, Shared::roster());
        $this->assertCount(9, Shared::roster('ed'));
        $this->assertCount(7, Shared::roster('dps'));
        $this->assertCount(7, Shared::roster('dpp'));
        foreach (['ed', 'dps', 'dpp'] as $post) {
            $this->assertCount(1, Shared::roster($post, true), "one current $post");
        }
        $ed = Shared::currentHolder('ed');
        $this->assertSame('Police Senior Colonel Kongkrissada Kittithiraphong', $ed['full_name']);
        $this->assertSame('Royal Thai Police (RTP)', $ed['force']);
        $this->assertSame('9th', Shared::roster('ed', null, true)[0]['ordinal']);
        $this->assertSame('2026–present', Shared::currentHolder('dpp')['term']);
    }

    public function test_partners_and_dialogue(): void
    {
        $this->assertCount(18, Shared::partners('lme'));
        $this->assertSame('https://aptahome.org/', Shared::partner('apta')['website']);
        $this->assertSame('Association of Police Training Institutions in Asia', Shared::partner('apta')['full_name']);
        $this->assertCount(12, Shared::dialogue('dialogue'));
        $this->assertCount(11, Shared::dialogue('observer'));
        $this->assertTrue(Shared::dialogueEntry('rcmp')['is_observer']);
        $this->assertNotSame(Shared::dialogueEntry('afp-australia')['name'], Shared::dialogueEntry('afp-argentina')['name']);
    }

    public function test_is_default_helpers(): void
    {
        $this->assertTrue(Shared::isDefault('secretariat', ['contact.phone', 'contact.fax']));
        $this->assertTrue(Shared::rowIsDefault('members', 'lao', ['tel', 'ncb_email']));
        $this->assertFalse(Shared::rowIsDefault('members', 'atlantis', ['tel']));
        $this->assertTrue(Shared::rowIsDefault('roster', 'ed|9th', ['name', 'term_start']));
    }
}
