<?php include 'header.php' ?>
<main class="flex-grow pb-10">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 mt-8 px-4 sm:px-8">Hello <?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></h3>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mt-8 mb-6 px-4 sm:px-8">
        <a href="/events/create" class="inline-flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-all hover:shadow-md no-underline">
            <span class="mr-2 text-lg">➕</span> Create Event
        </a>

        <form action="/events" method="GET" class="relative flex w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input
                    type="text"
                    name="keyword"
                    placeholder="ค้นหากิจกรรมที่น่าสนใจ..."
                    value="<?= htmlspecialchars($keyword ?? '') ?>"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all shadow-sm" />
            </div>
            <button
                type="submit"
                class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
                Search
            </button>
        </form>
    </div>

    <?php if (empty($events)) : ?>
        <p class="text-gray-500 bg-white p-8 rounded-lg border border-gray-200 text-center mx-4 sm:mx-8 shadow-sm">No events found.</p>
    <?php else : ?>
        <ul class="px-4 sm:px-8 pb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 m-0 p-0">
            <?php foreach ($events as $event) : ?>
                <li style="margin-bottom:10px;" class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-400 transition-all list-none flex flex-col overflow-hidden h-full">

                    <div class="h-48 w-full bg-gray-100 overflow-hidden border-b border-gray-100">
                        <?php
                        $images = getImagesByEventId($event['id']);
                        if (!empty($images)) :
                        ?>
                            <img src="<?= $images[0] ?>" alt="Event Image" class="w-full h-full object-cover">
                        <?php else : ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                <span class="text-3xl mb-1">🖼️</span>
                                <span class="text-xs">No Image</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <strong class="mb-2 block">
                                <a href="/events/<?= $event['id'] ?>/detail" class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors no-underline">
                                    <?= htmlspecialchars($event['name']) ?>
                                </a>
                            </strong>

                            <small class="text-gray-600 text-sm font-medium">
                                📅 <?= htmlspecialchars($event['event_start']) ?>
                                <br>
                                🏁 <?= htmlspecialchars($event['event_end']) ?>

                                <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500 font-normal">
                                    👤 Created by: <?= htmlspecialchars($event['creator_name']) ?>
                                </div>
                            </small>
                        </div>

                        <div class="mt-6">
                            <?php
                            // 1. เช็คก่อนเลยว่า Login หรือยัง
                            $is_logged_in = isset($_SESSION['user_id']);
                            $is_creator = $is_logged_in && ($event['creator_id'] == $_SESSION['user_id']);
                            $is_joined = isset($joined_events) && in_array($event['id'], $joined_events);

                            if (!$is_logged_in) : ?>
                                <a href="/events/<?= $event['id'] ?>/detail"
                                    class="block text-center bg-gray-400 text-white font-bold py-2.5 rounded-lg shadow-sm cursor-not-allowed select-none">
                                    👤 Login to Join
                                </a>

                            <?php elseif ($is_joined) : ?>
                                <span class="block text-center bg-gray-400 text-white font-bold py-2.5 rounded-lg shadow-sm cursor-not-allowed select-none">
                                    ✅ Joined
                                </span>

                            <?php elseif ($is_creator) : ?>
                                <a href="/events/<?= $event['id'] ?>/detail"
                                    class="block text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg shadow-sm transition-colors no-underline">
                                    👑 View Details
                                </a>

                            <?php else : ?>
                                <a href="/join_event/<?= $event['id'] ?>/join"
                                    class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-sm transition-colors no-underline">
                                    🤝 Join Event
                                </a>

                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php include 'footer.php' ?>