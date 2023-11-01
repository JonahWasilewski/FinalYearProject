def merge(list1, list2):

    list3 = []

    while len(list1) != 0 and len(list2) != 0:
        if list1[0] <= list2[0]:
            list3.append(list1[0])               # First element of list1 is smaller so gets added to list 3
            list1.pop(0)                                # Remove this element from list1
        else:
            list3.append(list2[0])               # First element of list2 is smaller so gets added to list 3
            list2.pop(0)

    if len(list1) == 0:
        list3.extend(list2)                             # List2 still has elements in it so needs to be added to list3 to end the merge
    else:
        list3.extend(list1)  

    return list3

def mergeSort(listToSort, start, end):

    if start < end:
        a = mergeSort(listToSort, start, start + (end - start) // 2)
        b = mergeSort(listToSort, start + (end - start) // 2 + 1, end)
        c = merge(a, b)

        return c
    
    return [listToSort[start]]

if __name__ == "__main__":

    merge([1],[2])

    listToSort = [4,2,3,5,6,7,8,9,5,3,4,5,6,7,8,3,2,77,7775,32,3,45,6]
    start = 0
    end = len(listToSort) - 1
    sortedList = mergeSort(listToSort, start, end)

    for i in sortedList:
        print(i)
