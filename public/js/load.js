function getHtml(load) {
      
      let classList = 'row-6 px-4 py-2 w-1/12 font-semibold text-base text-gray-700 border border-gray-700 text-center'; 
      
      const editUrl = `/edit/${load.id}`;
      
      const isPending = load.content && load.content.includes('待ち');
      const isPlace = load.place && load.place.includes('品川');
      const isDelivery = load.remarks && (load.remarks.includes('WS') || load.remarks.includes('SC'));
      const isBadge = load.is_new == 1;
      
      if (isPending) {
        classList += ' bg-red-100';
      }
      if (isPlace) {
        classList += ' bg-green-100';
      }
      if (isDelivery) {
        classList += ' bg-blue-100';
      }
      if (isBadge) {
        classList += ' bg-yellow-200';
      }

      return `
            <tr class="transition-colors duration-300" onclick="markAsSeen('${load.id}')">
                <td class="row-${load.id} ${classList}">
                    <button type="button" onclick="location.href='${editUrl}'" class="inline-flex ml-4 mb-2 text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">編集</button>
                    <button id="toggle-button-${load.id}" type="button" class="inline-flex ml-4 text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg" onclick="toggleComplete('${load.id}')">完了</button>
                </td>
                <td class="row-${load.id} ${classList}">${load.receiving || ''}</td>
                <td class="row-${load.id} ${classList}">${load.name || ''}</td>
                <td class="row-${load.id} ${classList}">${load.nameKana|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.number|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.content|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.charge|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.issue|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.remarks|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.place|| ''}</td>
            </tr>
        `;
    }

    const refresh = 5000;
    async function fetchUsers() {
      const currentParams = new URLSearchParams(window.location.search).toString();
      
      const apiBaseUrl = window.Laravel.apiBaseUrl;
      console.log(apiBaseUrl);
      const apiUrl = `${apiBaseUrl}/api/loadings/data?${currentParams}`;
      const response = await fetch(apiUrl);
      
      const json = await response.json();
      const data = json.loadings;
      const loadElement = document.getElementById('load');

      let rowData = '';

      for (const loadData of data) {
        rowData += getHtml(loadData);
      }

      loadElement.innerHTML = rowData;
      
    }
    
    fetchUsers();

    setInterval(fetchUsers, refresh);