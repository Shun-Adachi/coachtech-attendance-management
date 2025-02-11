<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Carbon\Carbon;

class DateTimeDisplayTest extends DuskTestCase
{
    use RefreshDatabase;

    /**
     * シーディングを有効にするための設定
     *
     * @return bool
     */
    protected function shouldSeed()
    {
        return true;
    }

    /**
     * 現在の日時情報がUIと同じ形式で出力されているか検証するテスト
     *
     * テスト手順:
     * 1. 勤怠打刻画面 (/attendance) を開く
     * 2. 画面に表示されている日時情報（#currentDate, #currentTime）を確認する
     *
     * 期待挙動:
     * 画面上に表示される日時が、現在の日時（例："2023年6月1日(木)"、"08:00"）と一致する
     */
    public function testCurrentDateTimeDisplayed()
    {
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        // テスト用の日時をセット
        Carbon::setTestNow(Carbon::create(2024, 2, 11, 14, 30)); // 2024年2月11日 14:30

        // 勤怠画面を開く
        $response = $this->get('/attendance');

        // Blade に表示される日付フォーマット
        $expectedDate = "2024年2月11日(日)"; // Blade でのフォーマットに合わせる
        $expectedTime = "14:30";

        // 日付が含まれていることを確認
        $response->assertSee($expectedDate);
        $response->assertSee($expectedTime);
    }

}
