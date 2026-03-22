using System.Collections.Generic;
using System.Threading;
using System.Threading.Tasks;

namespace Data;

public interface IGameRepository
{
    Task<int> SaveGameAsync(
        string plateau,
        IReadOnlyList<GameMove> moves,
    IReadOnlyList<GameLine> lines,
    int lignesJ1,
    int lignesJ2,
        CancellationToken cancellationToken = default);
}
